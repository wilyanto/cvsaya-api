<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvDocumentations extends Model
{
    use HasFactory;

    protected $table = 'cv_documentations';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'user_id',
        'identity_picture_card',
        'selfie_front',
        'selfie_left',
        'selfie_right',
        'mirrage_certificate',
    ];
}
