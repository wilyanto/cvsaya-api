<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use OwenIt\Auditing\Contracts\Auditable;

class Position extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

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
        return $this->belongsTo(Department::class, 'department_id', 'id')->withDefault();
    }

    public function levels()
    {
        return $this->belongsTo(Level::class, 'level_id', 'id')->withDefault();
    }

    public function ExpectedPositions()
    {
        return $this->hasMany(CvExpectedJob::class, 'expected_position', 'id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id')->withDefault();
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
            'max_salary' => $this->max_salary,
            'company' => $this->company,
        ];
    }
}
