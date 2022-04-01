<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    public $fillable = [
        'id',
        'name',
    ];

    public const PAYSLIP = 5;
}
