<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnnouncementStoreRequest;
use App\Http\Requests\AnnouncementUpdateRequest;
use App\Http\Resources\AnnouncementResource;
use App\Services\AnnouncementService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    use ApiResponser;

    protected $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    public function index()
    {
        $announcements = $this->announcementService->getAll();

        return $this->showAll(collect(AnnouncementResource::collection($announcements)));
    }

    public function store(AnnouncementStoreRequest $request)
    {
        $announcement = $this->announcementService->createAnnouncement($request);

        return $this->showOne(new AnnouncementResource($announcement));
    }

    public function show($id)
    {
        $announcement = $this->announcementService->getById($id);

        return $this->showOne(new AnnouncementResource($announcement));
    }

    public function update(AnnouncementUpdateRequest $request, $id)
    {
        $announcement = $this->announcementService->updateAnnouncement($request, $id);

        return $this->showOne(new AnnouncementResource($announcement));
    }

    public function destroy($id)
    {
        $message = $this->announcementService->deleteById($id);

        return response()->json(null, 204);
    }
}
