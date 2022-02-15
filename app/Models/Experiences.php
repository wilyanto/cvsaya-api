<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experiences extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cvsaya_experiences';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo(User::class,'id_kustomer','user_id');
    }
}
