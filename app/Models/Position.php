<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    public $fillable = [
        'id',
        'name',
        'department_id',
        'level_id',
        'parent_id',
        'min_salary',
        'max_salary',
        'company_id'
    ];
    protected $guard = 'id';

    protected $priamryKey = 'id';


    public function children()
    {
        return $this->hasMany(Position::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Position::class, 'parent_id');
    }

    public function getAllChildren()
    {
        $sections = new Collection();

        foreach ($this->children as $section) {
            $sections->push($section);
            $sections = $sections->merge($section->getAllChildren());
        }

        return $sections;
    }

    public function departments()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function levels()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id');
    }

    public function ExpectedPositions()
    {
        return $this->hasMany(CvExpectedSalary::class, 'expected_position', 'id');
    }

    public function toCandidate()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'department' => $this->departments,
            'level_id' => $this->levels,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'min_salary' => $this->min_salary,
            'max_salary' => $this->max_salary
        ];
    }

    // public function toArray()
    // {
    //     $company = $this->departments;
    //     // dd($company->name);
    //     return [
    //         'id' => $this->id,
    //         'name' => $this->name,
    //         'department_id' => $this->departments,
    //         'level' => $this->level(),
    //         'company' => $this->departments->company_id,
    //         'children' => $this->getAllChildren(),
    //     ];
    // }
}
