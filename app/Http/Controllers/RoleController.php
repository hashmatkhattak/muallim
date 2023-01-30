<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermissions;
use App\Models\TypePermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    function roles()
    {
        try {
            $data['roles'] = DB::table("roles")
                ->where("status", "!=", "2")
                ->get();
            return view("roles.all_roles", $data);
        } catch (\Exception $exception) {
            return redirect(route("roles"))->with("error", $exception->getMessage());
        }
    }

    function role_submitted(Request $request)
    {
        $data = $request->all();
        $role_id = isset($data['role_id']) ? $data['role_id'] : '';
        $role_name = isset($data['role_name']) ? $data['role_name'] : '';
        $role = new Role();
        if ($role_id != '') {
            $role = Role::query()->where("id", "=", $role_id)
                ->first();
        }
        $role->role_name = $role_name;
        $status = $role->save();
        if ($status && $role_id != '') {
            return redirect(route('roles'))->with('success', "Role is updated successfully");
        } else {
            return redirect(route('roles'))->with('success', "Role is added successfully");
        }

    }

    function change_role_status(Request $request){
        $type_id = $request->role_id;
        $status = $request->status;

        $role = Role::select("*")
            ->where("id", "=", $type_id)
            ->where("id", "!=", 1)
            ->first();
       // print_r($role);exit;
        if ($status != $role->status && ($status =='2')) {
            $role->status = $status;
            $role->save();
            return redirect(route('roles'))->with('success', 'Role is Deleted successfully');
        }

        if ($status != $role->status && ($status =='0')) {
            $role->status = $status;
            $role->save();
            return redirect(route('roles'))->with('success', 'Role is  DeActivated  successfully');
        }

        if ($status != $role->status && ($status =='1')) {
            $role->status = $status;
            $role->save();
            return redirect(route('roles'))->with('success', 'Role is Activated successfully');
        }
        return redirect(route('roles'))->with('error','oops..! something went wrong');
    }

    function permissions()
    {
        $data['permissions'] = Permission::query()
            ->select("*")
            ->orderBy("id", "DESC")
            ->get();
        return view("roles.permissions",$data);
    }

    function add_permission(Request $request)
    {
        try {
            $data = $request->all();
            $k = $j = 0;
            $role_id = isset($data['role_id']) ? $data['role_id'] : '';
            $data['role_name'] = Role::select('role_name')
                ->where('id', '=', $role_id)
                ->first();
            $permissions = DB::table("role_permissions")
                ->where("status", "!=", "2")
                ->orderBy('permission', 'ASC')
                ->get();

            $half = ceil($permissions->count() / 3);
            $data['half'] = $half;
            $data['chunks'] = $permissions->chunk($half);
            foreach ($data['chunks'][1] as $d) {
                $data['chunks'][1][$j] = $d;
                $j++;
            }
            foreach ($data['chunks'][2] as $d) {
                $data['chunks'][2][$k] = $d;
                $k++;
            }
            $data['allowed_permissions'] = DB::table("role_role_permissions")
                ->where("role_id", "=", $role_id)
                ->pluck('role_permissions_id')
                ->toArray();
            $data['role_id'] = $role_id;
            $data['permissions'] = $permissions;
            return view("roles.add_role_permissions", $data);
        } catch (\Exception $exception) {
            return redirect(route("roles"))->with("error", $exception->getMessage());
        }
    }

    function role_permission_submitted(Request $request)
    {

        $data = $request->all();
        $role_id = isset($data['role_id']) ? $data['role_id'] : '';
        $permission_id = isset($data['permission_id']) ? $data['permission_id'] : '';
        $is_checked = isset($data['is_checked']) ? $data['is_checked'] : '';
        if ($is_checked == 'true') {
            $role_permission = new RolePermissions();
            $role_permission->role_id = $role_id;
            $role_permission->role_permissions_id = $permission_id;
            $role_permission->save();
        } else {
            RolePermissions::query()->where("role_id", "=", $role_id)
                ->where("role_permissions_id", "=", $permission_id)
                ->delete();
        }
        return "1";
    }

    function role_details(Request $request)
    {
        $data = $request->all();
        $type_id= isset($data['type_id']) ? $data['type_id'] : '';
        $userTypes = Role::query()->where("id", "=", $type_id)
            ->first();
        return json_encode($userTypes);
    }

}
