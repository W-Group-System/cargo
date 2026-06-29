<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CronController extends Controller
{
    public function TestApi(Request $request){
        $response = [
            "message"=>"This is a test API",
            "isSuccess"=>true
        ];
        return response()->json($response,200);
    }
}
