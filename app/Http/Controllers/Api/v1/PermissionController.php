<?php

namespace App\Http\Controllers\api\v1;

use App\Models\EmployeeDetail;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    use ApiResponser;
    public function getPermission(){
        $user = auth()->user();

        $employee = EmployeeDetail::where('user_id',$user->id_kustomer)->firstOrFail();

        $data = [
            'role' => $employee->getRoleNames(),
            'permission' => $employee->getAllPermissions()->pluck('name')
        ];
        return $this->showOne(collect($data));
    }
}
