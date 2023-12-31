<?php

namespace App\Http\Controllers\Api\v1;;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\ShiftResource;
use App\Models\Attendance;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\EmployeeOneTimeShift;
use App\Models\EmployeeRecurringShift;
use App\Models\Position;
use App\Models\Shift;
use App\Traits\ApiResponser;

class ShiftController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $companyId = $request->company_id;
        $name = $request->name;
        $shifts = Shift::when($companyId, function ($query, $companyId) {
            $query->where('company_id', $companyId);
        })
            ->when($name, function ($query, $name) {
                $query->where('name', 'LIKE', '%' . $name . '%');
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

    public function show($id)
    {
        $shift = Shift::findOrFail($id);

        return $this->showOne($shift);
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
            'start_break' => 'nullable|date_format:H:i:s',
            'end_break' => 'nullable|date_format:H:i:s',
            'break_duration' => 'nullable|integer',
        ]);

        $shift = Shift::findOrFail($id)->update($request->all());

        return $this->showOne($shift);
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();

        return $this->showOne(null);
    }

    public function getShiftsByCompany($companyId)
    {
        $shifts = Shift::where('company_id', $companyId)->get();
        return $this->showAll($shifts);
    }

    public function getShift(Request $request)
    {
        $request->validate([
            'started_at' => 'required',
            'ended_at' => 'required',
            'company_id' => 'required|exists:companies,id',
        ]);

        $startDate = $request->started_at;
        $endDate = $request->ended_at;
        $companyId = $request->company_id;
        $positions = Position::where('company_id', $companyId)->pluck('id');
        $candidate = Candidate::where('user_id', auth()->id())->firstOrFail();
        $employee = Employee::where(
            'candidate_id',
            $candidate->id
        )->whereIn('position_id', $positions)->firstOrFail();

        if (!$employee) {
            return $this->errorResponse("Employee not found", 422, 42200);
        }

        $employeeShifts = $employee->getShifts($startDate);
        $data = [];
        foreach ($employeeShifts as $employeeShift) {
            $data[] = $employeeShift->shift;
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('shift_id', $employeeShift->shift->id)
                ->whereDate('date', $startDate)
                ->first();
            if ($attendance) {
                end($data)['attendance'] = new AttendanceResource($attendance);
            } else {
                end($data)['attendance'] = null;
            }
        }

        // return $this->showAll(collect(ShiftResource::collection($data)));
        return $this->showAll(collect($data));
    }
}
