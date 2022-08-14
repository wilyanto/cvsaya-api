<?php

namespace App\Services;

use App\Enums\AnnouncementEmployeeStatusEnum;
use App\Http\Common\Filter\FilterAnnouncementEmployeeSearch;
use App\Models\AnnouncementEmployee;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AnnouncementEmployeeService
{
    public function getAll($announcementId)
    {
        $announcementEmployees = QueryBuilder::for(AnnouncementEmployee::class)
            ->where('announcement_id', $announcementId)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterAnnouncementEmployeeSearch),
            ])
            ->allowedIncludes(['announcement', 'employee'])
            ->get();

        return $announcementEmployees;
    }

    public function getById($id)
    {
        $query = AnnouncementEmployee::where('id', $id);
        $announcementEmployee = QueryBuilder::for($query)
            ->allowedIncludes(['announcement', 'employee'])
            ->firstOrFail();

        return $announcementEmployee;
    }

    public function getUnreadAnnouncementByEmployeeId($employeeId)
    {
        $announcementEmployee = AnnouncementEmployee::where('employee_id', $employeeId)
            ->where('status', AnnouncementEmployeeStatusEnum::unread())
            ->oldest()
            ->first();

        return $announcementEmployee;
    }

    public function createAnnouncementEmployee($data, $announcementId)
    {
        $announcementEmployee = AnnouncementEmployee::create([
            'announcement_id' => $announcementId,
            'employee_id' => $data->employee_id,
        ]);

        return $announcementEmployee;
    }

    public function createAnnouncementEmployees($data, $announcementId)
    {
        $employeeIds = $data->employee_ids;
        $announcementEmployees = [];
        foreach ($employeeIds as $employeeId) {
            $announcementEmployee = AnnouncementEmployee::create([
                'announcement_id' => $announcementId,
                'employee_id' => $employeeId,
            ]);
            array_push($announcementEmployees, $announcementEmployee);
        }

        return $announcementEmployees;
    }

    public function updateAnnouncementEmployee($data, $id)
    {
        $announcementEmployee = $this->getById($id);
        $announcementEmployee->update([
            'announcement_id' => $data->announcement_id,
            'employee_id' => $data->employee_id,
        ]);

        return $announcementEmployee;
    }

    public function updateAnnouncementEmployeeNote($data, $id)
    {
        $announcementEmployee = $this->getById($id);
        $announcementEmployee->update([
            'note' => $data->note,
            'replied_at' => now()
        ]);

        return $announcementEmployee;
    }

    public function updateAnnouncementEmployeeStatus($id)
    {
        $announcementEmployee = $this->getById($id);
        $announcementEmployee->update([
            'status' => AnnouncementEmployeeStatusEnum::read(),
            'seen_at' => now()
        ]);

        return $announcementEmployee;
    }

    public function deleteById($id)
    {
        $announcementEmployee = AnnouncementEmployee::where('id', $id)->firstOrFail();
        $announcementEmployee->delete();
        return true;
    }
}
