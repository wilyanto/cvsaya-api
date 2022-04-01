<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public $fillable = [
        'file_name', 'mine_type', 'type_id', 'original_file_name'
    ];

}
