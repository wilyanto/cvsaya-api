<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotaType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority',
        'start_time',
        'end_time'
    ];
}
