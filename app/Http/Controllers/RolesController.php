<?php

namespace App\Http\Controllers;

use App\Classes\RolesAccessClass;
use App\Modules;
use App\Roles;
use App\RolesAccess;
use App\ShipmentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    private RolesAccessClass  $role;
    public function __construct(RolesAccessClass $roleClass)
    {
        $this->middleware('auth');
        $this->role = $roleClass;
    }
    public function index(Request $request)
    {
        $data = array();
        $data['ActiveModule'] = 'Roles';
        $data['shipmentStatusArr'] = ShipmentStatus::ShipmentStatusArray();

        return view('roles.index',$data);
    }
    public function RoleList(Request $request){
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

            $roleList = Roles::select("*");

            if (isset($request->id) && !empty($request->id)) {
                $roleList = $roleList->where("id",$request->id);
            }

            if (isset($request->search) && !empty(isset($request->search))) {
                $search = $request->search;
                $roleList = $roleList->where(function ($query) use ($search) {
                    $query->where('role_name', 'LIKE', "%{$search}%")
                        ->orWhere('role_description', 'LIKE', "%{$search}%");
                });
            }

            $totalCount = (clone $roleList)->count();

            $roleList = $roleList->orderBy("id","desc") 
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();
            $response["isSuccess"] = true;
            $response["message"] = "Successfully retrieved information.";
            $response["total"] = $totalCount;
            $response["data"] = $roleList;
        } catch (\Throwable $th) {
            Log::error("ERROR IN GETTING ROLE LIST: ".$th);
        }
        
        return $response;
    }

    public function SaveRole(Request $request){
        // dd($request->all());
        $isSuccess = false;
        $response = [
            "message"=>"Failed to save role.",
        ];

        try {

            $validate = Validator::make($request->all(), [
                'id' => ['nullable', 'integer', 'exists:roles,id'],
                'roleName' => ['required', 'string', 'max:255','unique:roles,role_name,' . ($request->id ?? 'NULL') . ',id'],
                'roleDescription' => ['required', 'string', 'max:255']
            ]);

            if ($validate->fails()) {
                
                Log::error("ERROR IN VALIDATION OF CREATING USER: ".$validate->errors());
                $response["errors"] = $validate->errors();
                return response()->json($response, 422);
            }

            if (isset($request->id) && !empty($request->id)) {
                $update = Roles::where('id',$request->id)
                    ->update([
                        "role_name"=>$request->roleName,
                        "role_description"=>$request->roleDescription
                    ]);
            }else{
                $create = Roles::create([
                    "role_name"=>$request->roleName,
                    "role_description"=>$request->roleDescription
                ]);
            }

            $response = [
                "message"=>"Saved successfully.",
            ];
            $isSuccess = true;
        } catch (\Throwable $th) {
            Log::error("ERROR IN SAVING ROLE: ".$th->getMessage());
        }
        
        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }

    public function RoleAccessList(Request $request){
        
        $data = [];
        $accessData = $this->role->GetUserAccessPerRoleV2($request->id);
        $data['roleId'] = $request->id;
        $data['access'] = $accessData;
        return view('roles.access',$data);
    }

    public function SaveRoleAccess(Request $request){

        $response=[
            "message"=>"Failed to save access."
        ];
        $isSuccess = false;

        try {
            $id = $request->id??"";
            $permission = $request->permission??[];
            if (!empty($id)) {
                if (count($permission) > 0) {
                    $deleteCurrentAccess = RolesAccess::where('role_id',$id)->delete();

                    foreach ($permission as $key => $value) {
                        $create = RolesAccess::create([
                            "role_id"=>$id,
                            "module_id"=>$key,
                            "can_read"=>isset($value['read']) && $value['read']=="1"?"1":"0",
                            "can_create"=>isset($value['create']) && $value['create']=="1"?"1":"0",
                            "can_update"=>isset($value['update']) && $value['update']=="1"?"1":"0",
                            "can_delete"=>isset($value['delete']) && $value['delete']=="1"?"1":"0"
                        ]);
                    }
                }
                $response=[
                    "message"=>"Successfully saved access."
                ];
                $isSuccess = true;
            }
        } catch (\Throwable $th) {
            Log::error("FAILED TO SAVE ROLE ACCESS: ".$th->getMessage());
        }

        if ($isSuccess) {
            return response()->json($response,200);
        }else{
            return response()->json($response,400);
        }
    }
}
