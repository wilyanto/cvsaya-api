<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SpecialityCertificates;

class Certifications extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cvsaya_certifications';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo(User::class,'id_kustomer','user_id');
    }

    public $fillable = [
        'id',
        'user_id',
        'name',
        'organization',
        'issued_at',
        'expired_at',
        'credential_id',
        'credential_url'
    ];

    public function speciality(){
        $this->hasMany(SpecialityCertificates::class,'id','certification_id');
    }
}
