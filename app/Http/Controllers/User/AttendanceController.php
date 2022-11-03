<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Http\Traits\Helpers;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    use Helpers;

    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
        $this->middleware('permission:attendance', ['only' => ['create', 'store', 'index', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('layouts.member.attendance.statement');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(AttendanceRequest $request)
    {
        $validated = $request->validated();
        $today = auth()->user()->today_attendance;
        if ($today && !is_null($today->check_out)) {
            return redirect()->back()->with('error', 'Already checked out: ' . date("g:iA", strtotime($today->check_out)));
        } elseif ($today) {
            return redirect()->back()->with('error', 'Already checked in: ' . date("g:iA", strtotime($today->check_in)));
        }
        try {
            $attendance = new Attendance();
            $attendance->last_updated_by = auth()->user()->id;
            $attendance->user_id = $validated['user_id'];
            $attendance->attendance_date = $validated['attendance_date'];
            if ($leave = array_key_exists('leave_status', $validated)) {
                $attendance->leave_status = $validated['leave_status'];
            }
            $attendance->check_in = $leave ? auth()->user()->working : $validated['check_in'];
            $attendance->attendance_status = !$leave;
            if ($attendance->save()) {
                return redirect()->back()->with(['success' => 'Check in time: ' . date("g:iA", strtotime($attendance->check_in))]);
            }
        } catch (\Exception $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance = $this->decryptFind(Attendance::class, $id);
        if (is_null($attendance->check_out)) {
            $attendance->check_out = date('H:i', strtotime(now()));
//         return  $attendance->working_hours = strtotime($attendance->check_out) - strtotime(date('H:i', strtotime($attendance->check_in)));
            $attendance->working_hours = round((Carbon::parse()->parse($attendance->attendance_date . ' ' . $attendance->check_in)->diffInMinutes(Carbon::parse()->parse(now()))) / 60, 1);
            $attendance->save();
            return redirect()->back()->with('success', 'Checkout time: ' . date("g:iA", strtotime($attendance->check_out)));
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function statement(Request $request)
    {
        $daterange = $request->daterange;
        if ($daterange) {
            if ($daterange == '') {
                return back()->with('exception', 'Please select the Date Range');
            } else {
                $dates = explode(' - ', $daterange);
                $date1 = $dates[0];
                $date2 = $dates[1];

                $startdate = date("Y-m-d", strtotime($date1));
                $enddate = date("Y-m-d", strtotime($date2));
            }
        } else{
            $currentTime = \Carbon\Carbon::now(); //returns current day
            $startdate = Carbon::parse($currentTime->firstOfMonth()->format('Y-m-d'));
            $enddate = Carbon::parse($currentTime->lastOfMonth()->format('Y-m-d'));
        }
        $attendance = Attendance::whereBetween('attendance_date', [$startdate, $enddate])->where('user_id', auth()->user()->id)->get();
        return view('layouts.member.attendance.statement', compact('attendance', 'daterange'));

    }
}
