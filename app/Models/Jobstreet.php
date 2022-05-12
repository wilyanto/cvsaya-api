<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobstreet extends Model
{
    use HasFactory;

    protected $connection = 'data_bank';
    protected $table = 'jobstreet';
}