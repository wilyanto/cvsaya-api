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
        // dump($data);
        $data['identity_picture_card'] = explode("/", $data['identity_picture_card'])[5];
        $data['selfie_front'] = explode("/", $data['selfie_front'])[5];
        $data['selfie_left'] = explode("/", $data['selfie_left'])[5];
        $data['selfie_right'] = explode("/", $data['selfie_right'])[5];
        // dd($data);
        $document = CvDocumentations::where('user_id',$data['user_id'])->first();
        if(!$document){
            $document = CvDocumentations::create($data);
        }
        $document->update($data);
        // dd($document);
        return $this->showOne($document);
    }

    public function random4Digits(){
        $digits = 4;
        $randomValue = rand(pow(10, $digits-1), pow(10, $digits)-1);

        return $randomValue;
    }

    public function uploadStorage(Request $request){
        $user = auth()->user();

        $request->validate([
            'file' => 'file|required',
            'type' => 'string|required',
        ]);

        $extension = $request->file('file')->getClientOriginalExtension();

        $filename = date('Y-m-d_H-i-s',time()).'_'.$user->id_kustomer.'_'.$this->random4Digits().'.'.$extension;

        $path = 'http://'.env('APP_URL').'/storage/'.$request->type.'/'.$filename;
        // dd(env('APP_URL'));
        $pathFormStorage = $request->file('file')->storeAs('public/'.$request->type,$filename);

        $data = [
            'filePath' => $path,
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
