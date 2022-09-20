<?php

namespace App\Services;

use App\Enums\AnnouncementEmployeeStatusEnum;
use App\Http\Common\Filter\FilterAnnouncementEmployeeSearch;
use App\Models\AnnouncementEmployee;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AnnouncementEmployeeService
{
    public function getAll()
    {
        $announcementEmployees = QueryBuilder::for(AnnouncementEmployee::class)
            ->allowedFilters([
                AllowedFilter::exact('announcement_id'),
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
        $announcementEmployees = QueryBuilder::for(AnnouncementEmployee::class)
            ->allowedIncludes(['announcement'])
            ->allowedFilters([
                AllowedFilter::exact('status')
            ])
            ->where('employee_id', $employeeId)
            ->oldest()
            ->get();

        return $announcementEmployees;
    }

    public function createAnnouncementEmployee($data)
    {
        $announcementEmployee = AnnouncementEmployee::create([
            'announcement_id' => $data->announcement_id,
            'employee_id' => $data->employee_id,
        ]);

        return $announcementEmployee;
    }

    public function createAnnouncementEmployees($data)
    {
        $employeeIds = $data->employee_ids;
        $announcementEmployees = [];
        foreach ($employeeIds as $employeeId) {
            $announcementEmployee = AnnouncementEmployee::create([
                'announcement_id' => $data->announcement_id,
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
            'note' => $data->note,
            'replied_at' => now(),
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
