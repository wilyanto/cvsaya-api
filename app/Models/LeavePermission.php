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

    public function occasion()
    {
        return $this->hasOne(LeavePermissionOccasion::class, 'id', 'occasion_id');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'leave_permission_documents');
    }
}
