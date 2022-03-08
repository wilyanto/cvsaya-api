<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Position;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // <------------------------------- this one
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EmployeeDetail extends Authenticatable
{
    use HasFactory,SoftDeletes;
    use CrudTrait; // <----- this
    use HasRoles;

    protected $table = 'employee_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'position_id',
        'salary',
    ];

    public function Position(){
        return $this->hasOne(Position::class,'id','position_id');
    }

    public function CvProfileDetail(){
       return $this->hasOne(CvProfileDetail::class,'user_id','user_id');
    }

    public function Company(){
        return $this->hasOneThrough(Company::class,Position::class,'id','id','position_id','company_id');
    }

    public function getCompanyName(){
        return $this->company->name;
    }

    public function getUserName(){
        return $this->CvProfileDetail->first_name;
    }

    // public function getCompanyName(){
    //     $company = $this->hasManyThrough(Company::class,Position::class);
    //     // return $position;
    //     return $company['name'];
    // }
}
