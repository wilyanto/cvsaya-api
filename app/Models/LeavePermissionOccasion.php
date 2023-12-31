<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePermissionOccasion extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'max_day', 'company_id'];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
