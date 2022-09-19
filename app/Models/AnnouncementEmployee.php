<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'employee_id',
        'note',
        'replied_at',
        'status',
        'seen_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'seen_at' => 'datetime'
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
