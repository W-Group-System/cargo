<?php

namespace App\Http\Controllers;
use App\Classes\OrderClass;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected OrderClass $orderClass;
    public function __construct(OrderClass $orderClass)
    {
        $this->middleware('auth');
        $this->orderClass = $orderClass;
    }

    public function orderReport(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Order';
        $data['canCreate'] = $request->create;
        $data['canUpdate'] = $request->update;
        $data['canDelete'] = $request->delete;
        // return view('report.orderReport', $data);

        return view('report.orders', $data);
    }

    public function shipmentReport(Request $request)
    {   
        $data = array();
        $data['ActiveModule'] = 'Shipment';
        $data['canCreate'] = $request->create;
        $data['canUpdate'] = $request->update;
        $data['canDelete'] = $request->delete;
        return view('report.shipment', $data);

    }

    public function index(Request $request)
    {
        //
         
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
