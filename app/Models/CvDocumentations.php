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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'identity_picture_card' => 'http://'.env('APP_URL').'/storage/'.'ktp'.'/'.$this->identity_picture_card,
            'selfie_front' => 'http://'.env('APP_URL').'/storage/'.'selfie'.'/'.$this->selfie_front,
            'selfie_left' => 'http://'.env('APP_URL').'/storage/'.'selfie'.'/'.$this->selfie_left,
            'selfie_right' => 'http://'.env('APP_URL').'/storage/'.'selfie'.'/'.$this->selfie_right,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
