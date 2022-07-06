<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlastTypeRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'blast_type_id',
        'count',
        'duration',
    ];
}
