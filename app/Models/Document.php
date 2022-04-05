<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidGenerator;

class Document extends Model
{
    use HasFactory,UuidGenerator;

    protected $database = 'documents';

    protected $keytype = 'string';

    public $fillable = [
        'file_name', 'mime_type', 'type_id', 'original_file_name'
    ];

    public function toIdDocuments(){
        return [
            'id' => $this->id,
        ];
    }

}
