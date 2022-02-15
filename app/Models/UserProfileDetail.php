<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Positions;

class UserProfileDetail extends Model
{
    use HasFactory;

    protected $casts = [
        'selfie_picture' => 'array',
    ];

    protected $table = 'cvsaya_employee_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public function positions(){
        return $this->hasOne(Positions::class,'id','position_id');
    }
}
