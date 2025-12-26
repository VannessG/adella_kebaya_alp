<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Shift::with('branch');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('shift_day', [$request->start_date, $request->end_date]);
        }

        $shifts = $query->latest()->get();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.shifts.create', compact('branches'));
    }

    public function attendance(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'shift_day' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $branch = Branch::findOrFail($request->branch_id);
        $employees = Employee::where('branch_id', $branch->id)
                             ->where('is_active', true)
                             ->get();

        return view('admin.shifts.attendance', [
            'employees' => $employees,
            'branch' => $branch,
            'shift_day' => $request->shift_day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required',
            'shift_day' => 'required',
            'attendance_data' => 'required|array'
        ]);

        Shift::create($request->all());
        return redirect()->route('admin.shifts.index')->with('success', 'Rekapan shift berhasil disimpan.');
    }

    public function show(Shift $shift)
    {
        // Untuk melihat detail siapa saja yang hadir
        $employees = Employee::whereIn('id', array_keys($shift->attendance_data))->get();
        return view('admin.shifts.show', compact('shift', 'employees'));
    }
}