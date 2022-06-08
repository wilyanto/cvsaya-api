<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePermission extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    use HasFactory;

    // Tambah company_id?
    protected $fillable = [
        'employee_id',
        'started_at',
        'ended_at',
        'occasion_id',
        'reason',
        'status',
        'answered_at'
    ];

    public function occasion()
    {
        return $this->hasOne(LeavePermissionOccasion::class, 'id', 'occasion_id');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'leave_permission_documents');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function candidate()
    {
        return $this->hasOneThrough(Candidate::class, Employee::class, 'id', 'id', 'employee_id', 'candidate_id');
    }

    public function position()
    {
        return $this->hasOneThrough(Position::class, Employee::class, 'id', 'id', 'employee_id', 'position_id');
    }


    public function company()
    {
        return $this->hasOneDeep(
            Company::class,
            [Employee::class, Position::class],
            [
                'id',
                'id',
                'id'
            ],
            [
                'employee_id',
                'position_id',
                'company_id'
            ]
        );
    }
}
