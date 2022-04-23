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

class Employee extends Authenticatable implements Auditable
{
    use HasFactory, SoftDeletes;
    use CrudTrait;
    use HasRoles;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'employees';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $connection = 'mysql';

    public $fillable = [
        'position_id',
        'salary',
        'employement_type'
    ];

    public function position()
    {
        return $this->hasOne(Position::class, 'id', 'position_id')->withDefault();
    }

    public function profileDetail()
    {
        return $this->hasOne(CvProfileDetail::class, 'user_id', 'user_id')->withDefault();
    }

    public function company()
    {
        return $this->hasOneThrough(Company::class, Position::class, 'id', 'id', 'position_id', 'company_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_kustomer')->withDefault();
    }

    public function getCompanyName()
    {
        if ($this->company) {
            return $this->company->name;
        }
    }

    public function getPhoneNumber()
    {
        return $this->user->telpon;
    }

    public function getUserName()
    {
        if ($this->profileDetail) {
            return $this->profileDetail->first_name;
        }
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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'position' => $this->position->toArrayEmployee(),
            'first_name' => $this->profileDetail->first_name,
            'last_name' => $this->profileDetail->last_name,
            'salary' => $this->salary,
        ];
    }

    public function toArrayEmployee()
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'department' => $this->departments,
            'level' => $this->departments,
            'company' => $this->company,
        ];
    }

    // public function getMasterAttendanceType($attendType){
    //    if()
    // }

    // public function getShifts($startedAt, $endedAt)
    // {
    //     $startedAt = new \DateTime($startedAt);
    //     $endedAt = new \DateTime($endedAt);
    //     $shifts = [];
    //     $data = [];
    //     $shifts = $this->hasMany(ShiftPositions::class, 'position_id', 'position_id');
    //     $specialShifts = $this->hasMany(ShiftEmployee::class,'employee_id','id');
    //     $attendanceTypes = AttendanceType::all();
    //     $attendances = $this->hasMany(Attendance::class,)
    //     for ($date = $startedAt; $date <= $endedAt; $date->modify('+1 day')) {
    //         $data['date'] = $date->format('Y-m-d\TH:i:s.v\Z');
    //         $data['clock_in'] = '';
    //         $data['start_break'] = '';
    //         $data['end_break'] = '';
    //         $data['clock_out'] = '';
    //     }
    // }
}
