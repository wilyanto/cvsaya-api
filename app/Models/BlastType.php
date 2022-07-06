<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlastType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function credentials()
    {
        return $this->belongsToMany(CRMCredential::class, 'crm_credential_blast_types', 'blast_type_id', 'credential_id');
    }

    public function credentialBlastType()
    {
        // need to check by credential id aswell
        return $this->hasOne(CRMCredentialBlastType::class, 'blast_type_id', 'id');
    }

    public function blastTypeRules()
    {
        return $this->hasMany(BlastTypeRule::class, 'blast_type_id', 'id');
    }
}
