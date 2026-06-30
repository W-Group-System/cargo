<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
    public function TestApi(Request $request){
        $response = [
            "message"=>"This is a test API",
            "isSuccess"=>true
        ];
        Log::info("TEST API RAN SUCCESSFULLY: ".Carbon::now());
        return response()->json($response,200);
    }
}
