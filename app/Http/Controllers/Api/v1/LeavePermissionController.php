<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeavePermissionStoreRequest;
use App\Http\Resources\LeavePermissionResource;
use App\Models\LeavePermission;
use App\Models\LeavePermissionOccasion;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enums\LeavePermissionStatusType;
use App\Http\Requests\LeavePermissionUpdateRequest;
use App\Models\Candidate;
use App\Models\CvDocument;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\LeavePermissionDocument;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;
use Intervention\Image\ImageManagerStatic as Image;

class LeavePermissionController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // date range?
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $employee = Employee::where('candidate_id', $candidate->id)->firstOrFail();
        $leavePermissions = LeavePermission::where('employee_id', $employee->id)->get();

        return $this->showAll(collect(LeavePermissionResource::collection($leavePermissions)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeavePermissionStoreRequest $request)
    {
        $leavePermissionOccasion = LeavePermissionOccasion::findOrFail($request->occasion_id);
        $startDate = Carbon::parse($request->started_at);

        if ($startDate->diff(now())->days <= $leavePermissionOccasion->max_day) {
            return $this->errorResponse("You need to ask for permissions sooner", 422, 42200);
        }

        $documentIds = $request->document_ids;
        // in case need to handle file upload
        // $documentType = DocumentType::where('name', 'Permission')->firstOrFail();
        // if ($request->has('files')) {
        //     $images = $request->file('files');
        //     foreach ($images as $image) {
        //         $extension = $image->extension();
        //         $mimeType = $image->getMimeType();
        //         $fileNameWithoutExtension = now()->valueOf();
        //         $fileName = $fileNameWithoutExtension . '.' . $image->extension();
        //         $img = Image::make($image)->encode($image->extension(), 70);
        //         Storage::disk('public')->put('images/leave_permission/' . $fileName, $img);
        //         $document = Document::create([
        //             'file_name' => $fileNameWithoutExtension,
        //             'user_id' => auth()->id(),
        //             'mime_type' => $mimeType,
        //             'type_id' => $documentType->id,
        //             'original_file_name' => $image->getClientOriginalName(),
        //         ]);
        //         $documentIds[] = $document->id;
        //     }
        // }

        $leavePermission = LeavePermission::create([
            'started_at' => $request->started_at,
            'ended_at' => $request->ended_at,
            'employee_id' => $request->employee_id,
            'occasion_id' => $request->occasion_id,
            'reason' => $request->reason,
            'status' => LeavePermissionStatusType::waiting()
        ]);

        foreach ($documentIds as $documentId) {
            LeavePermissionDocument::create([
                'leave_permission_id' => $leavePermission->id,
                'document_id' => $documentId
            ]);
        }

        return $this->showOne(new LeavePermissionResource($leavePermission));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leavePermission = QueryBuilder::for(LeavePermission::class)
            ->allowedIncludes(['occasion'])
            ->findOrFail($id);

        return $this->showOne(new LeavePermissionResource($leavePermission));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeavePermissionUpdateRequest $request, $leavePermissionId)
    {
        $leavePermission = LeavePermission::findOrFail($leavePermissionId);
        if (
            $leavePermission->status == LeavePermissionStatusType::accepted() ||
            $leavePermission->status == LeavePermissionStatusType::declined()
        ) {
            return $this->errorResponse("Leave Permission is either Rejected or Accepted !", 422, 42200);
        }

        $leavePermissionOccasion = LeavePermissionOccasion::findOrFail($request->occasion_id);
        $startDate = Carbon::parse($request->started_at);

        if ($startDate->diff(now())->days <= $leavePermissionOccasion->max_day) {
            return $this->errorResponse("You need to ask for permissions sooner", 422, 42200);
        }

        $documentIds = $request->document_ids;
        // in case need to handle file upload
        // $documentType = DocumentType::where('name', 'Permission')->firstOrFail();
        // if ($request->hasFile(('files'))) {
        //     $images = $request->file('files');
        //     foreach ($images as $image) {
        //         $mimeType = $image->getMimeType();
        //         $fileNameWithoutExtension = now()->valueOf();
        //         $fileName = $fileNameWithoutExtension . '.' . $image->extension();
        //         $img = Image::make($image)->encode($image->extension(), 70);
        //         Storage::disk('public')->put('images/leave_permission/' . $fileName, $img);
        //         $document = Document::create([
        //             'file_name' => $fileNameWithoutExtension,
        //             'user_id' => auth()->id(),
        //             'mime_type' => $mimeType,
        //             'type_id' => $documentType->id,
        //             'original_file_name' => $image->getClientOriginalName(),
        //         ]);
        //         $documentIds[] = $document->id;
        //     }
        // }

        // delete previous image(s)
        // foreach ($leavePermission->documents as $document) {
        //     Storage::disk('public')->delete('images/leave_permission/' . $document->file_name . '.' . $document->getExtension($document->mime_type));
        //     $leavePermission->documents()->detach([$document->id]);
        //     $document->delete();
        // }


        // delete previous image(s)
        $oldDocuments = $leavePermission->documents;
        $oldDocumentIds = [];
        foreach ($oldDocuments as $oldDocument) {
            if (!in_array($oldDocument->id, $documentIds)) {
                $oldDocumentIds[] = $oldDocument->id;
            }
        }

        foreach ($oldDocuments->whereIn('id', $oldDocumentIds) as $document) {
            Storage::disk('public')->delete('images/leave_permission/' . $document->file_name . '.' . $document->getExtension($document->mime_type));
            $leavePermission->documents()->detach([$document->id]);
            $document->delete();
        }

        $leavePermission->update([
            'started_at' => $request->started_at,
            'ended_at' => $request->ended_at,
            'employee_id' => $request->employee_id,
            'occasion_id' => $request->occasion_id,
            'reason' => $request->reason,
        ]);

        $leavePermission->documents()->sync($documentIds);

        return $this->showOne(new LeavePermissionResource($leavePermission));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leavePermission = LeavePermission::findOrFail($id);
        $leavePermission->delete();

        return $this->showOne(null);
    }

    // admin only
    public function updateLeavePermissionStatus(Request $request)
    {
        $status = $request->status;
        $leavePermissionId = $request->leave_permission_id;
        $leavePermission = LeavePermission::findOrFail($leavePermissionId);
        if (
            $leavePermission->status == LeavePermissionStatusType::accepted() ||
            $leavePermission->status == LeavePermissionStatusType::declined()
        ) {
            return $this->errorResponse("Leave Permission is either Rejected or Accepted !", 422, 42200);
        }
        $leavePermission->update(['status' => $status, 'answered_at' => now()]);

        return $this->showOne(new LeavePermissionResource($leavePermission->load('occasion')));
    }
}
