<?php

namespace App\Traits;

use App\Models\Candidate;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Spatie\Permission\Exceptions\UnauthorizedException;

trait HasRoleAndPermission
{
    protected function hasRole(array $roles, $idKustomer)
    {
        $candidate = Candidate::where('user_id', $idKustomer)->first();
        if (!$candidate) {
            return false;
        }
        $employee = Employee::where('candidate_id', $candidate->id)->first();
        if (!$employee) {
            return false;
        }
        if (!$employee->hasAnyRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }
    }

    protected function hasPermission(array $permissions, $idKustomer)
    {
        $candidate = Candidate::where('user_id', $idKustomer)->first();
        if (!$candidate) {
            return false;
        }
        $employee = Employee::where('candidate_id', $candidate->id)->first();
        if (!$employee) {
            return false;
        }
        $permissionsUser = collect($employee->getAllPermissions()->pluck('name'))->toArray();
        $intersectPermission = array_intersect($permissionsUser, $permissions);
        if (count($intersectPermission)) {
            return true;
        } else {
            return false;
        }
    }
}
