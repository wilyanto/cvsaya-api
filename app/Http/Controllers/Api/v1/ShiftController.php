<?php

namespace App\Http\Controllers\Api\v1;;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Shift;
use App\Models\ShiftPositions;
use App\Traits\ApiResponser;
use DateTimeZone;

class ShiftController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $data = [];
        $user = auth()->user();

        $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();

        $request->validate([
            'date' => 'date_format:Y-m-d\TH:i:s.u\Z|nullable',
        ]);

        $date =  new \DateTime("today", new DateTimeZone('Asia/Jakarta'));
        if ($request->date) {
            $date = new \DateTime($request->date, new DateTimeZone('Asia/Jakarta'));
        }
        $employee = $employee->getShift($date->format('Y-m-d\TH:i:s.u\Z'));
        $employee = $employee->shift;
        $data = [
            'id' => $employee->id,
            'name' => $employee->name,
            'clock_in' => date('Y-m-d\TH:i:s.v\Z', strtotime($date->format('Y-m-d') . ' ' . $employee->clock_in)),
            'clock_out' => date('Y-m-d\TH:i:s.v\Z', strtotime($date->format('Y-m-d') . ' ' . $employee->clock_out)),
            'break_started_at' => $employee->break_started_at
                ? date('Y-m-d\TH:i:s.v\Z', strtotime($date->format('Y-m-d') . ' ' . $employee->break_started_at))
                : null,
            'break_ended_at' =>  $employee->break_ended_at
                ? date('Y-m-d\TH:i:s.v\Z', strtotime($date->format('Y-m-d') . ' ' . $employee->break_ended_at))
                : null,
            'break_duration' => $employee->break_duration,
            'created_at' => date('Y-m-d\TH:i:s.v\Z',strtotime($employee->created_at)),
            'updated_at' => date('Y-m-d\TH:i:s.v\Z',strtotime($employee->updated_at)),
        ];
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
