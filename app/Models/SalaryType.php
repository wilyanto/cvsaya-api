<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryType extends Model
{
    use HasFactory;
    public $fillable = [
        'name',
        'code',
        'type',
        'company_id',
    ];

    public function toArrayDefault()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company_id' => $this->company_id,
        ];
    }
}
