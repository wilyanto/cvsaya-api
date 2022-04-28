<?php

namespace App\Http\Controllers\Api\v1;;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceType;
use App\Models\Employee;
use App\Models\ShiftEmployee;
use App\Models\ShiftPositions;
use App\Traits\ApiResponser;
use DateInterval;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    use ApiResponser;

    public function index()
    {
        $data = [];
        $user = auth()->user();

        $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();

        $attendanceTypes = AttendanceType::all();

        $date =  new \DateTime("today", new DateTimeZone('Asia/Jakarta'));
        $startDate = $date->format('Y-m-d\TH:i:s.u\Z');
        $interval = DateInterval::createFromDateString('+23 hour +59 minute + 59 second');
        $endDate = $date->add($interval)->format('Y-m-d\TH:i:s.u\Z');
        $shift = ShiftEmployee::whereBetween(
            'date',
            [
                $startDate,
                $endDate
            ]
        )->first();
        if (!$shift) {
            $getTodayDay = $date->format('N');
            $shift = ShiftPositions::where('day', $getTodayDay)->where('position_id', $employee->position->id)->first();
        }
        $data['employee'] = [
            'id' => $employee->id,
            'name' => $employee->getUserName(),
        ];
        foreach ($attendanceTypes as $attendanceType) {
            $columnName = $attendanceType->name;
            $data[$attendanceType->name] = $shift->shift->$columnName;
        }
        return $this->showOne($data);
    }
}
