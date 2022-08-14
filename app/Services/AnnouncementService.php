<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\AnnouncementEmployee;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AnnouncementService
{
    public function getAll()
    {
        $announcements = QueryBuilder::for(Announcement::class)
            ->allowedIncludes(['company'])
            ->allowedFilters([
                AllowedFilter::exact('company_id'),
            ])
            ->get();

        return $announcements;
    }

    public function getById($id)
    {
        $query = Announcement::where('id', $id);
        $announcement = QueryBuilder::for($query)
            ->allowedIncludes(['company'])
            ->firstOrFail();

        return $announcement;
    }

    public function createAnnouncement($data)
    {
        $announcement = Announcement::create([
            'company_id' => $data->company_id,
            'title' => $data->title,
            'body' => $data->body,
            'created_by' => $data->created_by
        ]);

        return $announcement;
    }

    public function updateAnnouncement($data, $id)
    {
        $announcement = $this->getById($id);
        $announcement->update([
            'company_id' => $data->company_id,
            'title' => $data->title,
            'body' => $data->body,
            'created_by' => $data->created_by
        ]);

        return $announcement;
    }

    public function deleteById($id)
    {
        $announcement = Announcement::where('id', $id)->firstOrFail();
        $announcement->delete();
        return true;
    }
}
