<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\CvDocumentation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Traits\ApiResponser;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Return\Exception;

class CvDocumentationController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $user = auth()->user();
        if (!$id) {
            $id = $user->id_kustomer;
        }

        $getDocuments = CvDocumentation::where('user_id', $id)->firstOrFail();
        return $this->showOne($getDocuments);
    }

    public function getByDefault()
    {
        return $this->index(null);
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

        $validateURL = 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';

        $request->validate([
            'identity_picture_card' => 'required', $validateURL,
            'selfie_front' => 'required', $validateURL,
            'selfie_left' => 'required',  $validateURL,
            'selfie_right' => 'required',  $validateURL,
        ]);
        $data = $request->all();
        $data['user_id'] = $user->id_kustomer;
        // dump($data);
        $data['identity_picture_card'] = explode("/", $data['identity_picture_card'])[5];
        $data['selfie_front'] = explode("/", $data['selfie_front'])[5];
        $data['selfie_left'] = explode("/", $data['selfie_left'])[5];
        $data['selfie_right'] = explode("/", $data['selfie_right'])[5];
        // dd($data);
        $document = CvDocumentation::where('user_id', $data['user_id'])->first();
        if (!$document) {
            $document = CvDocumentation::create($data);
        }
        $document->update($data);
        // dd($document);
        return $this->showOne($document);
    }

    public function random4Digits()
    {
        $digits = 4;
        $randomValue = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

        return $randomValue;
    }

    public function uploadStorage(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'file' => 'file|required',
            'type' => [
                'required',
                Rule::in(['identity_card', 'front_selfie', 'left_selfie', 'right_selfie'])
            ],
        ]);

        $extension = $request->file('file')->getClientOriginalExtension();

        $filename = date('Y-m-d_H-i-s', time()) . '_' . $user->id_kustomer . '_' . $this->random4Digits() . '.' . $extension;

        $path = env('APP_URL') . '/storage/' . $request->type . '/' . $filename;
        $pathFormStorage = $request->file('file')->storeAs('public/' . $request->type, $filename);

        $document = CvDocumentation::where('user_id', $user->id_kustomer)->first();

        if (!$document) {
            $document = new CvDocumentation;
        }

        switch ($request->type) {
            case 'identity_card':
                $document->identity_card = $filename;
                break;
            case 'front_selfie':
                $document->front_selfie = $filename;
                break;
            case 'left_selfie':
                $document->left_selfie = $filename;
                break;
            case 'right_selfie':
                $document->right_selfie = $filename;
                break;
            default:
                null;
        }
        $document->save();
        return $this->showOne($document);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Documentations  $documentations
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'type' => [
                'required',
                Rule::in(['identity_card', 'front_selfie', 'left_selfie', 'right_selfie'])
            ]
        ]);
        $document = CvDocumentation::where('user_id', $user->id_kustomer)->firstOrFail();
        $filename = null;
        switch ($request->type) {
            case 'identity_card':
                $filename = $document->identity_card;
                break;
            case 'front_selfie':
                $filename = $document->front_selfie;
                break;
            case 'left_selfie':
                $filename = $document->left_selfie;
                break;
            case 'right_selfie':
                $filename = $document->right_selfie;
                break;
            default:
                null;
        }
        try {
            $path = public_path() . '/storage/' . $request->type . '/' . $filename;
            if (!$path) {
            }
            return Response::download($path);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404, 40400);
        }
    }


    public function showById(Request $request,$id){
        $request->validate([
            'type' => [
                'required',
                Rule::in(['identity_card', 'front_selfie', 'left_selfie', 'right_selfie'])
            ]
        ]);
        $document = CvDocumentation::where('user_id', $id)->firstOrFail();
        $filename = null;
        switch ($request->type) {
            case 'identity_card':
                $filename = $document->identity_card;
                break;
            case 'front_selfie':
                $filename = $document->front_selfie;
                break;
            case 'left_selfie':
                $filename = $document->left_selfie;
                break;
            case 'right_selfie':
                $filename = $document->right_selfie;
                break;
            default:
                null;
        }
        try {
            $path = public_path() . '/storage/' . $request->type . '/' . $filename;
            if (!$path) {
            }
            return Response::download($path);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 404, 40400);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Documentations  $documentations
     * @return \Illuminate\Http\Response
     */
    public function edit(CvDocumentation $documentations)
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
    public function update(Request $request, CvDocumentation $documentations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Documentations  $documentations
     * @return \Illuminate\Http\Response
     */
    public function destroy(CvDocumentation $documentations)
    {
        //
    }
}
