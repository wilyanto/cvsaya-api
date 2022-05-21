<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CvSosmed extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'cv_social_medias';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'candidate_id',
        'instagram',
        'tiktok',
        'youtube',
        'facebook',
        'website_url',
    ];

    public function profileDetails()
    {
        return $this->belongsTo(CvProfileDetail::class, 'candidate_id', 'candidate_id')->withDefault();
    }
}
