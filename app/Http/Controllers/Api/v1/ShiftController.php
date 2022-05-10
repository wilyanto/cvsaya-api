<?php

namespace App\Http\Controllers\Api\v1;;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShiftRequest;
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
        $companyId = $request->company_id;
        $shifts = Shift::when($companyId, function ($query, $companyId) {
            $query->where('company_id', $companyId);
        })
            ->with('company')
            ->paginate($request->input('page_size', 10));

        return $this->showPagination('shifts', $shifts);
        // $data = [];
        // $user = auth()->user();

        // $employee = Employee::where('user_id', $user->id_kustomer)->firstOrFail();

        // $request->validate([
        //     'date' => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
        // ]);

        // $date =  new \DateTime("today", new DateTimeZone('Asia/Jakarta'));
        // if ($request->date) {
        //     $date = new \DateTime($request->date, new DateTimeZone('Asia/Jakarta'));
        // }
        // $employee = $employee->getShift($date->format('Y-m-d\TH:i:s.u\Z'));
        // $employee = $employee->shift;
        // $data = [
        //     'id' => $employee->id,
        //     'name' => $employee->name,
        //     'clock_in' => $employee->clock_in,
        //     'clock_out' => $employee->clock_out,
        //     'break_started_at' => $employee->break_started_at,
        //     'break_ended_at' =>  $employee->break_ended_at,
        //     'break_duration' => $employee->break_duration,
        //     'created_at' => $employee->created_at,
        //     'updated_at' => $employee->updated_at,
        // ];
        // return $this->showOne($data);
    }

    public function store(StoreShiftRequest $request)
    {
        $shift = Shift::create($request->all());

        return $this->showOne($shift);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string',
            'clock_in' => 'nullable|date_format:H:i:s',
            'clock_out' => 'nullable|date_format:H:i:s',
            'break_started_at' => 'nullable|date_format:H:i:s',
            'break_ended_at' => 'nullable|date_format:H:i:s',
            'break_duration' => 'nullable|integer',
        ]);

        $shift = Shift::findOrFail($id)->update($request->all());

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
