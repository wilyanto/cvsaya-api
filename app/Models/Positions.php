<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Positions extends Model
{
    use HasFactory;

    protected $table = 'cvsaya_positions';

    public $fillable = [
        'id',
        'name',
        'department_id',
        'level',
        'parent_id',
    ];
    protected $guard = 'id';

    protected $priamryKey = 'id';


    public function children ()
    {
        return $this->hasMany(Positions::class,'parent_id');
    }

    public function parent ()
    {
        return $this->belongsTo(Positions::class,'parent_id');
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

    public function departments(){
        return $this->belongsTo(Departments::class,'department_id','id');
    }

    public function levels(){
        return $this->belongsTo(Level::class,'level_id','id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' =>$this->name,
            'department' => $this->departments,
            'level' => $this->levels,
            'parent_id'=>$this->parent_id,
            'created_at' => $this->created_at,
            'updated_at'=>$this->updated_at,
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
