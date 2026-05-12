<?php

namespace App\Classes;

use App\Modules;
use App\RolesAccess;
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

    public static function GetUserAccessPerRoleV2($roleId){
        
        $moduleListArr = [];
        try {
            $headerList = Modules::select('id','module_name')
                ->where('status','A')
                ->where('is_header','1')
                ->orderBy('header_order','asc')
                ->get()
                ->map(function($q) use(&$moduleListArr,$roleId){
                    $accessListArr = [];
                    $moduleList = Modules::with(['RolesAccess' => function($q) use ($roleId) {
                        $q->where('role_id',$roleId);
                    }])
                    ->where('status','A')
                    ->where('is_header','<>','1')
                    ->where('header_id',$q->id)
                    ->orderBy('module_order','asc')
                    ->get()
                    ->map(function($qm)use(&$accessListArr,$roleId){
                        $roleAccessData = RolesAccess::select(
                            'module_id',
                            'can_create',
                            'can_read',
                            'can_update',
                            'can_delete',
                            'm.module_name',
                            'm.module_url',
                            'm.icon'
                        )->leftJoin('modules as m','m.id','roles_access.module_id')->where("role_id",$roleId)->where("module_id",$qm->id)->first();
                        if (!empty($roleAccessData)) {
                             $accessListArr[$qm->module_name]=[
                                "module_id"=>$qm->id,
                                "url"=>$qm->module_url,
                                "icon"=>$qm->icon,
                                "canCreate"=>$roleAccessData->can_create,
                                "canRead"=>$roleAccessData->can_read,
                                "canUpdate"=>$roleAccessData->can_update,
                                "canDelete"=>$roleAccessData->can_delete,
                             ];
                        }else{
                            $accessListArr[$qm->module_name]=[
                                "module_id"=>$qm->id,
                                "canCreate"=>"0",
                                "canRead"=>"0",
                                "canUpdate"=>"0",
                                "canDelete"=>"0",
                             ];
                        }
                        return $accessListArr;
                    });
                    $moduleListArr[$q->module_name] = $accessListArr;
                    return $moduleListArr; 
                });
            // dd($moduleListArr);
        } catch (\Throwable $th) {
            Log::info("ERROR IN GETTING ROLES ACCESS PER USER: ".$th->getMessage());
        }

        $result = $moduleListArr;

        return $result;
    }
}