<?php

namespace App\Http\Controllers;
use App\Cargo;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CargoController extends Controller
{
    public function index(Request $request)
    {
        $data = array();
        $data['cargoActive'] = true;

        $query = Order::query();

        // Entries per page
        $entries = $request->input('number_of_entries', 10);

        // Date filter (created_at)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$start, $end]);
        }

        $cargoes = $query
            ->orderBy('created_at', 'desc')
            ->paginate($entries)
            ->appends($request->all());

        return view('cargo.index', compact('cargoes'),$data);
    }

    public function CargoList(Request $request){
        $response = [
            "isSuccess"=>false,
            "message"=>"Failed to retrieve information.",
            "total"=>0,
            "page"=>1,
            "data"=>null
        ];
        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;

            $ordersList = Order::select("*");

            if (isset($request->id) && !empty($request->id)) {
                $ordersList = $ordersList->where("id",$request->id);
            }

            if (isset($request->search) && !empty(isset($request->search))) {
                $search = $request->search;
                $ordersList = $ordersList->where(function ($query) use ($search) {
                    $query->where('CardCode', 'LIKE', "%{$search}%")
                        ->orWhere('CardName', 'LIKE', "%{$search}%")
                        ->orWhere('Label', 'LIKE', "%{$search}%")
                        ->orWhere('Packaging', 'LIKE', "%{$search}%")
                        ->orWhere('DocNum', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end   = Carbon::parse($request->end_date)->endOfDay();

                $ordersList->whereBetween('created_at', [$start, $end]);
            }

            $totalCount = (clone $ordersList)->count();

            $ordersList = $ordersList->orderBy("id","desc") 
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();
            $response["isSuccess"] = true;
            $response["message"] = "Successfully retrieved information.";
            $response["total"] = $totalCount;
            $response["data"] = $ordersList;
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING CARGO LIST: ".$th);
        }
        
        return $response;
    }
}
