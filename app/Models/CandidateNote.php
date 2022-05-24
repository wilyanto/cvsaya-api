<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CandidateNote extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'candidate_notes';

    public $fillable = [
        'note',
        'employee_id',
        'candidate_id',
        'visibility',
    ];

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'id', 'candidate_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function profileDetail()
    {
        return $this->hasOneThrough(
            CvProfileDetail::class,
            Candidate::class,
            'id',
            'candidate_id',
            'candidate_id',
            'id'
        );
    }

    public function employeeProfileDetail()
    {
        return $this->hasOneThrough(
            CvProfileDetail::class,
            Employee::class,
            'id',
            'user_id',
            'employee_id',
            'user_id',
        );
    }
}
