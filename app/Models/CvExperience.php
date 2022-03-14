<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\EmploymentType;
use Illuminate\Database\Eloquent\SoftDeletes;

class CvExperience extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cv_experiences';

    protected $guard = 'id';

    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo(User::class,'id_kustomer','user_id');
    }

    public function EmployeeType(){
        return $this->hasOne(EmploymentType::class,'id','employment_type_id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id'=> $this->user_id,
            'position' => $this->position,
            'employment_type_id' => $this->EmployeeType,
            'location' => $this->location,
            'start_at' => $this->start_at,
            'until_at' => $this->until_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
