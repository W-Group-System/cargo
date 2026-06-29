<?php

namespace App\Http\Middleware;

use App\RolesAccess;
use Closure;
use Illuminate\Support\Facades\Auth;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = "/".$request->path();
        $roleId = Auth::user()->role;

        $roleAccessData = RolesAccess::from('roles_access as ra')
                            ->select('m.module_url','ra.can_create','ra.can_read','ra.can_update','ra.can_delete')
                            ->leftJoin('modules as m','m.id','=','ra.module_id')
                            ->where("ra.role_id",$roleId)
                            ->where("m.module_url",$path)
                            ->first();
        if (!empty($roleAccessData)) {
            if ($roleAccessData->can_read == "1" ) {
                $request->merge([
                    'create' => $roleAccessData->can_create == "1"?true:false,
                    'update' => $roleAccessData->can_update == "1"?true:false,
                    'delete' => $roleAccessData->can_delete == "1"?true:false
                ]);
                return $next($request);
            }else{
                return redirect()->back()->with('error', "You don't have permission to access this page.");
            }
        }else{
            return redirect()->back()->with('error', "You don't have permission to access this page.");
        }
    }
}
