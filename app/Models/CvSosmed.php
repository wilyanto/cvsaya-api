<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvSosmed extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'cv_social_medias';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'id',
        'user_id',
        'instagram',
        'tiktok',
        'youtube',
        'facebook',
        'website_url',
    ];

    public function profileDetails(){
        return $this->belongsTo(CvProfileDetail::class,'user_id','user_id');
    }
}
