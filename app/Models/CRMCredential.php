<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMCredential extends Model
{
    use HasFactory;

    const URL_SEND_MESSAGE = 'http://localhost:8001/api/v1/send-whatsapp-message';
    const URL_SEND_BATCH_MESSAGE = 'http://localhost:8001/api/v1/batch/send-whatsapp-message';

    protected $table = 'crm_credentials';

    protected $fillable = [
        'name',
        'key',
        'country_code',
        'phone_number',
        'quantity',
        'type'
    ];

    public function blastTypes()
    {
        return $this->belongsToMany(BlastType::class, 'crm_credential_blast_type', 'crm_credential_id', 'blast_type_id');
    }
}
