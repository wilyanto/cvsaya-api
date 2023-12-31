<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePermissionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_permission_id',
        'document_id'
    ];
}
