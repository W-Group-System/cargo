<?php

namespace App\Http\Controllers;
use App\Cargo;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
