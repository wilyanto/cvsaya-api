<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidGenerator;

class Document extends Model
{
    use HasFactory,UuidGenerator;

    protected $database = 'documents';

    public $fillable = [
        'file_name', 'mine_type', 'type_id', 'original_file_name'
    ];

}
