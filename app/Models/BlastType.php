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
}
