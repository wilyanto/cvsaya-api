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
        'priority'
    ];

    public function credentials()
    {
        return $this->belongsToMany(CRMCredential::class, 'crm_credential_blast_type', 'blast_type_id', 'crm_credential_id');
    }
}
