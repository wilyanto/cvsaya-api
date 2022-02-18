<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Positions;

class CvProfileDetail extends Model
{
    use HasFactory;

    protected $casts = [
        'selfie_picture' => 'array',
    ];

    public $fillable = [
        'about',
        'user_id',
        'website_url',
        'selfie_about',
        'religion',
        'reference',
        'expected_position',
        'reason_position',
        'about_position',
        'indetification_number',
        'birth_date',
        'location_birth',
    ];

    protected $table = 'profile_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public function positions(){
        return $this->hasOne(Positions::class,'id','position_id');
    }
}
