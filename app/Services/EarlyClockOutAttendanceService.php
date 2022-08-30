<?php

namespace App\Services;

use App\Enums\EarlyClockOutAttendanceStatusEnum;
use App\Http\Common\Filter\FilterEarlyClockOutSearch;
use App\Http\Common\Filter\FilterEarlyClockOutStatus;
use App\Http\Common\Filter\FilterPenaltySearch;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\EarlyClockOutAttendance;
use App\Models\PayrollPeriod;
use App\Models\Penalty;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EarlyClockOutAttendanceService
{
    public function getAll($companyId, $pageSize)
    {
        $employeeIds = Company::findOrFail($companyId)->employees()->pluck('employees.id');
        $attendances = QueryBuilder::for(Attendance::class)
            ->whereHas('clockOutAttendanceDetail.earlyClockOutAttendance')
            ->whereIn('employee_id', $employeeIds)
            ->allowedFilters(
                [
                    AllowedFilter::custom('status', new FilterEarlyClockOutStatus),
                    AllowedFilter::custom('search', new FilterEarlyClockOutSearch)
                ]
            )
            ->paginate($pageSize);

        return $attendances;
    }

    public function getById($id)
    {
        $query = EarlyClockOutAttendance::where('id', $id);
        $earlyClockOutAttendance = QueryBuilder::for($query)
            ->firstOrFail();

        return $earlyClockOutAttendance;
    }

    public function updateStatus($data, $id)
    {
        $earlyClockOutAttendance = $this->getById($id);
        $earlyClockOutAttendance->update([
            'status' => $data->status,
        ]);

        if ($data->status == EarlyClockOutAttendanceStatusEnum::rejected()) {
            $verified_at = null;
        } else {
            $verified_at = now();
        }

        $earlyClockOutAttendance->attendanceDetail->update([
            'verified_at' => $verified_at,
            'verified_by' => $data->approved_by
        ]);

        return $earlyClockOutAttendance;
    }

    public function deleteById($id)
    {
        $earlyClockOutAttendance = EarlyClockOutAttendance::findOrFail($id);
        $earlyClockOutAttendance->delete();
        return true;
    }
}
