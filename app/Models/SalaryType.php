<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'type',
        'is_adhocable'
    ];

    protected $casts = [
        'is_adhocable' => 'boolean'
    ];
}
