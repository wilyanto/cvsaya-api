<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePermission extends Model
{
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
}
