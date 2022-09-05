<?php

namespace App\Models;

use App\Enums\SalaryTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Model implements Auditable
{
    use HasFactory;
    use CrudTrait; // <----- this
    use HasRoles;

    use \OwenIt\Auditing\Auditable;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    protected $primaryKey = 'id';

    protected $table = 'companies';

    public $incrementing = false;

    public $fillable = [
        'id', 'name'
    ];

    public function employees()
    {
        return $this->hasManyThrough(Employee::class, Position::class, 'company_id', 'position_id', 'id', 'id');
    }

    public function attendances()
    {
        return $this->hasManyDeep(
            Attendance::class,
            [Position::class, Employee::class, AttendanceEmployee::class],
            ['company_id', 'position_id', 'employee_id', 'id'],
            ['id', 'id', 'id', 'attendance_id']
        )->with(['employee.company']);
    }

    public function getEmployeeAttendances($startDate, $endDate)
    {
        return $this->attendances()->whereBetween('scheduled_at', [$startDate, $endDate])->get();
    }

    public function resignations()
    {
        return $this->hasManyDeep(
            EmployeeResignation::class,
            [
                Position::class,
                Employee::class,
            ],
        );
    }

    public function companySalaryTypes()
    {
        return $this->hasMany(CompanySalaryType::class);
    }

    public function getDeductionCompanySalaryTypes()
    {
        return $this->companySalaryTypes()->whereRelation('salaryType', 'type', SalaryTypeEnum::deduction())->get();
    }
}
