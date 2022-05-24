<?php

namespace App\Http\Middleware;


use App\Models\Employee;
use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        $employee = Employee::where('candidate_id', $authGuard->user()->id_kustomer)->firstOrFail();
        $permissionsUser = collect($employee->getAllPermissions()->pluck('name'))->toArray();
        $intersectPermission = array_intersect($permissionsUser, $permissions);
        if (count($intersectPermission)) {
            return $next($request);
        }

        // foreach ($permissions as $permission) {
        // dump(EmployeeDetail::with('role')->get());
        //     if ($employee->can($permission)) {
        //         return $next($request);
        //     }
        // }

        throw UnauthorizedException::forPermissions($permissions);
    }
}
