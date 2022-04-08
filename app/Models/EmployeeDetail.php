<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Position;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeDetail extends Authenticatable implements Auditable
{
    use HasFactory, SoftDeletes;
    use CrudTrait;
    use HasRoles;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'employee_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $connection = 'mysql';

    public $fillable = [
        'position_id',
        'salary',
    ];

    public function position()
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    public function profileDetail()
    {
        return $this->hasOne(CvProfileDetail::class, 'user_id', 'user_id');
    }

    public function company()
    {
        return $this->hasOneThrough(Company::class, Position::class, 'id', 'id', 'position_id', 'company_id');
    }

    public function getCompanyName()
    {
        return $this->company->name;
    }

    public function getUserName()
    {
        return $this->profileDetail->first_name;
    }

    public function interviewerDetail()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'position' => $this->position,
            'first_name' => $this->profileDetail->first_name,
            'last_name' => $this->profileDetail->last_name
        ];
    }

    // public function getCompanyName(){
    //     $company = $this->hasManyThrough(Company::class,Position::class);
    //     // return $position;
    //     return $company['name'];
    // }
}
