<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Department extends Model implements Auditable
{
    use HasFactory, SoftDeletes;

    use \OwenIt\Auditing\Auditable;

    protected $table = 'departments';

    public $fillable = [
        'id',
        'name',
        'company_id',
    ];

    public function positions()
    {
        return $this->hasMany(Position::class, 'department_id', 'id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function toarrayIndex()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company' => $this->company,
            'total_employee' => count($this->positions),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function onlyNameAndId(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company' => $this->company,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
