<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMCredential extends Model
{
    use HasFactory;

    const URL_SEND_MESSAGE = '/api/v1/send-whatsapp-message';
    const URL_SEND_BATCH_MESSAGE = '/api/v1/batch/send-whatsapp-message';

    protected $table = 'crm_credentials';

    protected $fillable = [
        'name',
        'key',
        'country_code',
        'phone_number',
        'is_active',
        'expired_at',
        'last_updated_at',
        'scheduled_message_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expired_at' => 'datetime',
        'last_updated_at' => 'datetime',
    ];

    public function blastTypes()
    {
        return $this->belongsToMany(BlastType::class, 'crm_credential_blast_types', 'credential_id', 'blast_type_id');
    }

    public function blastLogs()
    {
        return $this->hasMany(CRMBlastLog::class, 'credential_id', 'id');
    }

    public function quotas()
    {
        return $this->hasMany(CRMCredentialQuotaType::class, 'credential_id', 'id');
    }

    public function getTodayBlastLogCount()
    {
        return $this->blastLogs()->whereDate('created_at', today())->count();
    }

    public function recentMessages()
    {
        return $this->blastLogs()->latest()->take(3);
    }

    public function getBlastTypeCount()
    {
        return $this->blastTypes()->count();
    }
}
