<?php

namespace App\Http\Controllers\Api\v1;;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceType;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Shift;
use App\Models\ShiftEmployee;
use App\Models\ShiftPositions;
use App\Traits\ApiResponser;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $data = [];
        $user = auth()->user();

        $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();

        $attendanceTypes = AttendanceType::all();
        $request->validate([
            'date' => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
        ]);

        $date =  new \DateTime("today", new DateTimeZone('Asia/Jakarta'));
        if ($request->date) {
            $date = new \DateTime($request->date, new DateTimeZone('Asia/Jakarta'));
        }
        $startDate = $date->format('Y-m-d\TH:i:s.v\Z');
        $interval = DateInterval::createFromDateString('+23 hour +59 minute + 59 second');
        $endDate = $date->add($interval)->format('Y-m-d\TH:i:s.v\Z');
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
            if (!$shift) {
                return $this->errorResponse('Your Shift Not Found', 422, 42201);
            }
        }
        $data['name'] = $shift->shift->name;
        $data['break_duration'] = $shift->shift->break_duration;
        foreach ($attendanceTypes as $attendanceType) {
            $columnName = $attendanceType->name;
            if($shift->shift->$columnName){
                // dump(strtotime($startDate) + strtotime($shift->shift->$columnName));
                $shiftByColumn = date('Y-m-d\TH:i:s.u\Z', strtotime($shift->shift->$columnName));
            }else{
               $shiftByColumn= $shift->shift->$columnName;
            }
            if ($attendanceType->name == AttendanceType::BREAKENDEDAT) {
                $attendance = Attendance::whereBetween('duty_at', [
                    $startDate,
                    $endDate
                ])->where('attendance_type_id', $attendanceType->id)->first();
                if ($attendance) {
                    $time = new \DateTime($attendance->checked_at, new DateTimeZone('Asia/Jakarta'));;
                    $shiftByColumn = $time->format('Y-m-d\TH:i:s.v\Z');
                }
            }
            $data[$attendanceType->name] = $shiftByColumn;
        }
        return $this->showOne($data);
    }

    public function store(Request $request)
    {
        $rule = [
            'name' => 'required|string',
            'clock_in' => 'required|date_format:H:i:s',
            'clock_out' => 'required|date_format:H:i:s.u',
            'break_started_at' => 'required|date_format:H:i:s',
            'break_duration' => 'required|integer',
            'company_id' => 'required',
        ];

        $request->validation($rule);

        $shifts = Shift::create($request->all());

        return $this->showOne($shifts);
    }

    public function update(Request $request, $id)
    {
        $rule = [
            'name' => 'required|string',
            'clock_in' => 'required|date_format:H:i:s',
            'clock_out' => 'required|date_format:H:i:s.u',
            'break_started_at' => 'required|date_format:H:i:s',
            'break_duration' => 'required|integer',
            'company_id' => 'required',
        ];

        $request->validation($rule);

        $shift = Shift::findOrFail($id);

        $shift = $shift->fill($request->all());
        if ($shift->isDirty()) {
            $shift->update($request->all());
            $shift->restore();
        }

        return $this->showOne($shift);
    }

    public function attachShiftPosition(Request $request, $id)
    {
        $rule = [
            "shifts" => [
                'array',
                'required'
            ]
        ];

        $request->validate($rule);
        $shift = Shift::findOrFail($id);
        foreach ($request->shifts as $newShift) {
            $position = Position::findOrFail($newShift->position_id);
            $exisitShift = ShiftPositions::where('position_id', $position->id)->whereIn('day', [$newShift->day])->get();
            if (count($exisitShift)) {
                return $this->errorResponse('founded antoher shift, please delete old one', 422, 42202);
            }
            $position = Position::findOrFail($newShift->position);
            foreach ($newShift->days as $day) {

                $data[] = [
                    'shift_id' => $shift->id,
                    'position_id' => $position->id,
                    'day' => $day,
                ];
            }
        }

        ShiftPositions::insert($data);
        return $this->showOne('Success');
    }

    public function attachShiftEmployee(Request $request, $id)
    {
        $rule = [
            "shifts" => [
                'array',
                'required'
            ]
        ];

        $request->validate($rule);
        $shift = Shift::findOrFail($id);
        foreach ($request->shifts as $newShift) {
            $employee = Employee::findOrFail($newShift->position);
            foreach ($newShift->days as $day) {
                $data[] = [
                    'shift_id' => $shift->id,
                    'employee_id' => $employee->id,
                    'day' => $day,
                ];
            }
        }

        ShiftPositions::insert($data);
        return $this->showOne('Success');
    }
}
