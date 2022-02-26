<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvDocumentations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Storage;

class CvDocumentationsController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $getDocuments = CvDocumentations::where('user_id', $user->id_kustomer)->first();
        if (!$getDocuments) {
            return $this->errorResponse('Document not found', 404, 40401);
        }

        return $this->showOne($getDocuments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'identity_picture_card' => 'required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
            'selfie_front' => 'required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
            'selfie_left' => 'required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
            'selfie_right' => 'required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        $document = CvDocumentations::where('user_id',$data['user_id'])->first();
        if(!$document){
            $document = CvDocumentations::create($data);
        }
        $document->update($data);
        // dd($document);
        return $this->showOne($document);
    }

    public function uploadStorage(Request $request){
        $user = auth()->user();

        $request->validate([
            'file' => 'file|required',
            'title' => 'string|required',
        ]);

        $extension = $request->file('file')->getClientOriginalExtension();
        $digits = 4;
        $randomValue = rand(pow(10, $digits-1), pow(10, $digits)-1);

        $filename = date('Y-m-d_H-i-s',time()).'_'.$user->id_kustomer.'_'.$randomValue.'.'.$extension;

        $path = $request->file('file')->storeAs('public/'.$request->title,$filename);

        $data = [
            'filePath' => $filename,
            'title' => $request->title,
        ];

        return $this->showOne($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Documentations  $documentations
     * @return \Illuminate\Http\Response
     */
    public function show(CvDocumentations $documentations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Documentations  $documentations
     * @return \Illuminate\Http\Response
     */
    public function edit(CvDocumentations $documentations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Documentations  $documentations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CvDocumentations $documentations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Documentations  $documentations
     * @return \Illuminate\Http\Response
     */
    public function destroy(CvDocumentations $documentations)
    {
        //
    }
}
