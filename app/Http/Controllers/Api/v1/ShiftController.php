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
            if($shift){
                return $this->errorResponse('Your Shift Not Found',422,42201);
            }
        }
        $data['employee'] = [
            'id' => $employee->id,
            'name' => $employee->getUserName(),
        ];
        foreach ($attendanceTypes as $attendanceType) {
            $columnName = $attendanceType->name;
            $shift = $shift->shift->$columnName;
            if ($attendanceType->name == AttendanceType::BREAKENDEDAT) {
                $attendance = Attendance::whereBetween('date', [
                    $startDate,
                    $endDate
                ])->first();
                if ($attendance) {
                    $shift = $attendance->checked_at;
                }
            }
            $data[$attendanceType->name] = $shift;
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

    // public function attachShiftPosition(Request $request,$id){
    //     $rule = [
    //         "shifts" => [
    //             'array',
    //             'required'
    //         ]
    //     ];

    //     $request->validate($rule);

    //     foreach($request->positions as $positionId){
    //         $position = Position::findOrFail($id);
    //         if(!ShiftPositions::where('shift_id',$shift->id)->where('position_id',$position->id)->first()){
    //             $data[] = [
    //                 'shift_id' => $shift->id,
    //                 'position_id' => $position->id,
    //                 // 'day' =>
    //             ]
    //         }
    //     }
    // }
}
