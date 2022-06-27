<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMBlastLog extends Model
{
    use HasFactory;

    protected $table = 'crm_blast_logs';

    protected $fillable = [
        'blast_loggable_type',
        'blast_loggable_id',
        'credential_id',
        'recipient_country_code',
        'recipient_phone_number',
        'blast_type_id',
        'message_param_value',
        'message_template'
    ];

    public function blastLoggable()
    {
        return $this->morphTo(__FUNCTION__, 'blast_loggable_type', 'blast_loggable_id');
    }
}
