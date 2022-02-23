<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvLogDocumentations extends Model
{
    use HasFactory;

    protected $table = 'cv_log_documentations';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
