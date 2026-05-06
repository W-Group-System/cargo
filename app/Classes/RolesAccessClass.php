<?php

namespace App\Classes;

use App\Modules;
use Illuminate\Support\Facades\Log;

class RolesAccessClass {

    public static function GetUserAccessPerRole($userRoleId){
        
        $result = [
            "isSuccess"=>true,
            "data"=>[]
        ];
        $accessData = [];
        try {
            $headerList = Modules::select('id','module_name')
                ->where('status','A')
                ->where('is_header','1')
                ->orderBy('header_order','asc')
                ->get()
                ->map(function($q) use(&$accessData,$userRoleId){
                    $totalModulesCanBeRead = 0;
                    $moduleList = Modules::with(['RolesAccess' => function($q) use ($userRoleId) {
                            $q->where('role_id',$userRoleId);
                        }])
                        ->where('status','A')
                        ->where('is_header','<>','1')
                        ->where('header_id',$q->id)
                        ->orderBy('module_order','asc')
                        ->get();
                    
                    foreach ($moduleList as $module) {
                        if($module->RolesAccess[0]['can_read'] == "1"){
                            $totalModulesCanBeRead++;
                        }
                    }

                    if ($totalModulesCanBeRead > 0) {
                        $accessData[$q->module_name] = $moduleList;
                    }
                        
                    return $accessData; 
                });
            
        } catch (\Throwable $th) {
            Log::info("ERROR IN GETTING ROLES ACCESS PER USER: ".$th->getMessage());
        }

        $result = $accessData;

        return $result;
    }
}