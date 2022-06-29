<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMCredentialBlastType extends Model
{
    use HasFactory;

    protected $table = 'crm_credential_blast_types';

    protected $fillable = [
        'credential_id',
        'blast_type_id',
        'priority'
    ];
}
