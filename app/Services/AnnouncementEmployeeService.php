<?php

namespace App\Services;

use App\Models\AnnouncementEmployee;
use Spatie\QueryBuilder\QueryBuilder;

class AnnouncementEmployeeService
{
    public function getAll()
    {
        $AnnouncementEmployees = QueryBuilder::for(AnnouncementEmployee::class)
            ->get();

        return $AnnouncementEmployees;
    }

    public function getById($id)
    {
        $query = AnnouncementEmployee::where('id', $id);
        $AnnouncementEmployee = QueryBuilder::for($query)
            ->firstOrFail();

        return $AnnouncementEmployee;
    }

    public function createAnnouncementEmployee($data)
    {
        $AnnouncementEmployee = AnnouncementEmployee::create([
            'announcement_id' => $data->announcement_id,
            'employee_id' => $data->employee_id,
        ]);

        return $AnnouncementEmployee;
    }

    public function updateAnnouncementEmployee($data, $id)
    {
        $AnnouncementEmployee = $this->getById($id);
        $AnnouncementEmployee->update([
            'company_id' => $data->company_id,
            'title' => $data->title,
            'body' => $data->body,
            'created_by' => $data->created_by
        ]);

        return $AnnouncementEmployee;
    }

    public function deleteById($id)
    {
        $AnnouncementEmployee = AnnouncementEmployee::where('id', $id)->firstOrFail();
        $AnnouncementEmployee->delete();
        return true;
    }
}
