<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMCredentialQuotaType extends Model
{
    use HasFactory;

    protected $table = 'crm_credential_quota_types';

    protected $fillable = [
        'credential_id',
        'quota_type_id',
        'quantity',
        'renew_at',
        'max_quantity'
    ];
}
