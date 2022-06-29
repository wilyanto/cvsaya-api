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
        'message_template',
        'status',
        'uuid',
        'expired_at',
        'priority'
    ];

    public function blastLoggable()
    {
        return $this->morphTo(__FUNCTION__, 'blast_loggable_type', 'blast_loggable_id');
    }

    //TODO: need to find better way, for now copy the same code from message service
    public function constructMessage()
    {
        $messageTemplate = json_decode($this->message_template, true);
        $message = $messageTemplate['body'];
        $messageParamValue = json_decode($this->message_param_value, true);
        $messageParamValueBody = $messageParamValue['body'];
        foreach ($messageParamValueBody as $key => $field) {
            $search = "{{" . $key . "}}";
            $message = str_replace($search, $field, $message);
        }
        return $message;
    }
}
