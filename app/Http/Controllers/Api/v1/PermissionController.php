<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;
use App\Http\Controllers\ResponseController;

class PermissionController extends ResponseController
{
    public function index(Request $request) {
        $query = Permission::latest('id');
        $name = $request->name;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = Permission::count();

        if($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $permissions = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'permissions' => PermissionResource::collection($permissions),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    public function give_permission(Request $request, $id) {
        $role = Role::findOrFail($id);
        if($role) {
            $permission = Permission::find($request->permission_id);
            if($permission) {
                $role->givePermissionTo($permission);
    
                $data = [
                    'permissions' => Role::with('permissions')->where('id', $id)->get(),
                    'message'     => "Permission Granted Successfully"
                ];
                return $this->successResponse($data);
            } else {
                $data = [];
                $error = [
                    'code' => 'E0004',
                    'message' => 'Permission not found',
                ];
                return $this->successResponse($data, $error);
            }
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Role not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function revoke_permission(Request $request, $id) {
        $role = Role::findOrFail($id);
        if($role) {
            $permission = Permission::find($request->permission_id);
            if($permission) {
                if($role->hasDirectPermission($permission)) {
                    $role->revokePermissionTo($permission->name);
                }
                $data = [
                    'permissions' => Role::with('permissions')->where('id', $id)->get(),
                    'message'     => "Permission Revoked Successfully"
                ];
                return $this->successResponse($data);
            } else {
                $data = [];
                $error = [
                    'code' => 'E0004',
                    'message' => 'Permission not found',
                ];
                return $this->successResponse($data, $error);
            }
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Role not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}
