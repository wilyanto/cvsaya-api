<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CvDocument extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'cv_documents';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $fillable = [
        'candidate_id',
        'identity_card',
        'front_selfie',
        'left_selfie',
        'right_selfie',
        'mirrage_certificate',
    ];

    public function identityCard()
    {

        return $this->hasOne(Document::class, 'id', 'identity_card');
    }

    public function frontSelfie()
    {
        return $this->hasOne(Document::class, 'id', 'front_selfie');
    }

    public function leftSelfie()
    {
        return $this->hasOne(Document::class, 'id', 'left_selfie');
    }

    public function rightSelfie()
    {
        return $this->hasOne(Document::class, 'id', 'right_selfie');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'candidate_id' => $this->candidate_id,
            'identity_card' => $this->identity_card,
            'front_selfie' => $this->front_selfie,
            'left_selfie' => $this->left_selfie,
            'right_selfie' => $this->right_selfie,
            'created_at' => $this->created_at,
            'updated_at' =>  $this->updated_at,
        ];
    }
}
