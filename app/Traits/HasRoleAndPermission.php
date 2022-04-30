<?php

namespace App\Traits;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Spatie\Permission\Exceptions\UnauthorizedException;

trait HasRoleAndPermission
{
    protected function hasRole(Array $roles,$idKustomer){
        $employee = Employee::where('user_id',$idKustomer)->first();
        if(!$employee){
            return false;
        }
        if (! $employee->hasAnyRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }
    }

    protected function hasPermission(Array $permissions,$idKustomer){
        $employee = Employee::where('user_id',$idKustomer)->first();
        if(!$employee){
            return false;
        }
        $permissionsUser = collect($employee->getAllPermissions()->pluck('name'))->toArray();
        $intersectPermission = array_intersect($permissionsUser,$permissions);
        if(count($intersectPermission)){
            return true;
        }else{
            return false;
        }
    }
}
