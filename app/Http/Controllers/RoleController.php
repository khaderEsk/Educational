<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{

    public function create(RoleRequest $request)
    {
        try {
            DB::beginTransaction();
            $permissions_id = $request->permissions_id;
            $permissions = Permission::whereIn('id', $permissions_id)->get();
            $data = Role::create([
                'name' => $request->name,
            ]);
            $data->syncPermissions($permissions);
            DB::commit();
            return $this->returnData($data,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(),'Please try again later');
        }
    }


    public function update($id,RoleRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = Role::find($id);
            if (!$data)
                return $this->returnError("401",'Not found');

            $permissions_id = $request->permissions_id;
            $permissions = Permission::whereIn('id', $permissions_id)->get();
            $data ->update([
                'name' => $request->name,
            ]);
            $data->syncPermissions($permissions);
            DB::commit();
            return $this->returnData($data,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError($ex->getCode(),'Please try again later');
        }
    }



    public function delete($id)
    {
        try {
            $data = Role::find($id);
            if (!$data)
                return $this->returnError("401",'Not found');

            $data->delete();
            return $this->returnData($data,'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),'Please try again later');
        }
    }
}
