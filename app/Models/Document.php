<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidGenerator;
use OwenIt\Auditing\Contracts\Auditable;

class Document extends Model implements Auditable
{
    use HasFactory, UuidGenerator;

    use \OwenIt\Auditing\Auditable;

    protected $database = 'documents';

    protected $keytype = 'string';

    public $fillable = [
        'user_id', 'file_name', 'mime_type',
        'type_id', 'original_file_name'
    ];

    public function toIdDocuments()
    {
        return [
            'id' => $this->id,
        ];
    }
}
