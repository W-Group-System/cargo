<?php

namespace App\Helpers;

use App\IncoTermsTrackingPoints;
use Illuminate\Support\Facades\Log;

class Helper{
    

    public static function LoadTrackingPointsPerIncoTerms(string $incoTerms,$params=["PortOfOrigin"=>"","PortOfDestination"=>""]){
        
        $response = [];
        $trackingPointsArr = [];
        $isFound = false;

        try {
            if (!empty($incoTerms)) {
                $incoTerms = strtoupper($incoTerms);
                $incoTermsData = IncoTermsTrackingPoints::pluck("tracking_points","inco_terms");
                foreach ($incoTermsData as $key => $value) {
                    if (str_contains($incoTerms,$key)) {
                        $explodeValue = explode("|",$value);
                        foreach ($explodeValue as $trackingKey => $trackingValue) {
                            foreach ($params as $paramKey => $paramValue) {
                                if ("{".$paramKey."}" == $trackingValue) {
                                    $trackingValue = !empty($paramValue)?$paramValue:$trackingValue;
                                }
                            }
                            $trackingPointsArr[$trackingValue] = $trackingValue;
                        }

                        if (count($trackingPointsArr) > 0) {
                            $response = $trackingPointsArr;
                        }
                        $isFound = true; 
                    }
                    if ($isFound) {
                        break;
                    }
                }
            }       
        } catch (\Throwable $th) {
            Log::error("ERROR IN HELPER FUNCTION - GETTING TRACKING POINTS PER INCO TERMS: ".$th->getMessage());
        }
        
        return $response;
    }
}