<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Level;
use App\Models\Position;
use  App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class LevelController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'companies' => [
                'array',
                'nullable'
            ]
        ]);
        $companies = $request->companies;
        $data = Level::where(function ($query) use ($companies) {
            if ($companies) {
                $query->whereIn('company_id', $companies);
            }
        })->get();
        $data = $data->map(function ($item) {
            return $item->toArrayIndex();
        });
        return $this->showAll($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'company_id' => 'string|required',
        ]);

        $create = Level::create($request->all());

        return $this->showOne($create);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CvSayaLevel  $cvSayaLevel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Level::findOrFail($id);
        return $this->showOne($data->toArrayIndex());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CvSayaLevel  $cvSayaLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(Level $cvSayaLevel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CvSayaLevel  $cvSayaLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|nullable',
            'company_id' => 'string|nullable',
        ]);
        $find = level::findOrFail($id);
        $find->update($request->all());

        return $this->showOne($find->toArrayIndex());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CvSayaLevel  $cvSayaLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $request->validate([
            'level_id' => 'nullable|exists:levels,id',
        ]);

        $find = Level::findOrFail($id);
        if ($request->level_id == $id) {
            return $this->errorResponse('level_od and id cannot be same', 422, 42201);
        }
        if ($request->level_id) {
            Position::where('level_id', $id)->update([
                'level_id' => $request->level_id,
            ]);
        } else {
            Position::where('level_id', $id)->update([
                'level_id' => null,
            ]);
        }
        $find->delete();

        return $this->showOne(null);
    }
}
