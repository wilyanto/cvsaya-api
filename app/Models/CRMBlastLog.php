<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMBlastLog extends Model
{
    use HasFactory;

    protected $table = 'crm_blast_logs';

    protected $fillable = [
        'model_type',
        'model_id',
        'sender_country_code',
        'sender_phone_number',
        'recipient_country_code',
        'recipient_phone_number',
        'blast_type_id',
        'message_variable',
        'message_template'
    ];

    public function CRMblastLoggable()
    {
        return $this->morphTo();
    }
}
