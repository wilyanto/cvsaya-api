<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Models\Candidate;

class PermissionController extends Controller
{
    use ApiResponser;
    public function getPermission()
    {
        $user = auth()->user();
        $candidate = Candidate::where('user_id', $user->id_kustomer)->firstOrFail();
        $employee = Employee::where('candidate_id', $candidate->id)->firstOrFail();

        $data = [
            'role' => $employee->getRoleNames(),
            'permission' => $employee->getAllPermissions()->pluck('name')
        ];
        return $this->showOne(collect($data));
    }
}
