<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCompanyGroup extends Model
{
    use HasFactory;

    protected $table = 'attendance_company_group';

    protected $fillable = [
        'company_id',
        'company_parent_id',
        'user_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function companyParent()
    {
        return $this->belongsTo(Company::class, 'company_parent_id');
    }
}
