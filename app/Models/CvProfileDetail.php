<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvProfileDetail extends Model
{
    use HasFactory;

    protected $casts = [
        'selfie_picture' => 'array',
    ];

    public $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birth_location',
        'birth_date',
        'gender',
        'identity_number',
        'religion',
        'religion',
    ];

    protected $table = 'cv_profile_details';

    protected $guard = 'id';

    protected $primaryKey = 'id';

}
