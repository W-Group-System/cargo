<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('number_of_entries', 10); // Default 10 per page
        $data = collect();  

        if ($request->has('sap_server')) {
            $sapServer = $request->sap_server;

            $client = new Client();

            try {
                switch ($sapServer) {
                    case 'whi':
                        $response = $client->request('GET', 'http://localhost/sap-database/public/api/salesorder');
                        break;
                    case 'pbi':
                        $response = $client->request('GET', 'https://sap-database.wgroup.space/api/pbi_salesorder');
                        break;
                    case 'ccc':
                        $response = $client->request('GET', 'https://sap-database.wgroup.space/api/ccc_salesorder');
                        break;
                    default:
                        $response = null;
                }

                if ($response && $response->getStatusCode() === 200) {
                    $body = $response->getBody()->getContents();
                    $allData = collect(json_decode($body));

                    // Date filter
                    $start = $request->start_date;
                    $end = $request->end_date;
                    if ($start && $end) {
                        $allData = $allData->filter(function ($item) use ($start, $end) {
                            $docDate = Carbon::parse($item->DocDate)->format('Y-m-d');
                            return $docDate >= $start && $docDate <= $end;
                        })->values();
                    }

                    // Search filter
                    if ($search) {
                        $allData = $allData->filter(function ($item) use ($search) {
                            $term = strtolower($search);
                            return str_contains(strtolower($item->DocNum), $term) ||
                                str_contains(strtolower($item->CardCode), $term) ||
                                str_contains(strtolower($item->CardName), $term);
                        })->values();
                    }

                    $data = $allData;
                }
            } catch (\Exception $e) {
                \Log::error('SAP API error: ' . $e->getMessage());
            }
        }

        // Manual pagination
        $page = $request->input('page', 1);
        $offset = ($page * $entries) - $entries;

        $pagedData = new LengthAwarePaginator(
            $data->slice($offset, $entries)->values(),
            $data->count(),
            $entries,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );

        if ($request->ajax()) {
            return view('orders._table', compact('pagedData'))->render();
        }

        return view('orders.index', [
            'data' => $pagedData,
            'entries' => $entries,
            'search' => $search
        ]);
    }
}

