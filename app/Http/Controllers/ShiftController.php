<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;

class ShiftController extends Controller{
    public function index(Request $request){
        // 1. Query Dasar dengan Filter Tanggal (jika ada)
        $query = Shift::with('branch')->latest('shift_day');

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('shift_day', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('shift_day', '<=', $request->end_date);
        }

        // 2. Ambil data (gunakan get() atau paginate() sesuai kebutuhan)
        // Di sini saya gunakan get() agar logika transform lebih mudah, tapi paginate() juga bisa dengan cara khusus
        $shifts = $query->get(); 
        // 3. Transform Data untuk View (Pindahkan Logika PHP View ke sini)
        $shifts->transform(function ($shift) {
            // Logika Format Tanggal
            $shift->view_date = $shift->shift_day->format('d/m/Y');
            // Logika Format Jam Kerja
            $start = substr($shift->start_time, 0, 5);
            $end = substr($shift->end_time, 0, 5);
            $shift->view_time = "$start - $end";
            // Logika Hitung Absensi
            $attendanceData = $shift->attendance_data ?? [];
            $shift->view_total_staff = count($attendanceData);
            // Hitung jumlah yang hadir
            $shift->view_present_count = collect($attendanceData)
                ->filter(fn($val) => $val === 'hadir')
                ->count();
            return $shift;
        });
        return view('admin.shifts.index', [
            'title' => 'Rekap Shift',
            'shifts' => $shifts
        ]);
    }

    public function show($id){
        // 1. Ambil data Shift
        $shift = Shift::with('branch')->findOrFail($id);

        // 2. Ambil pegawai di cabang tersebut
        // Pastikan model Employee diload (sesuaikan query jika Anda memfilter pegawai aktif saja)
        $employees = Employee::where('branch_id', $shift->branch_id)->get();

        // 3. LOGIKA DIPINDAHKAN KE SINI
        // Kita map/loop setiap pegawai untuk menentukan statusnya berdasarkan data di shift
        $employees->transform(function ($employee) use ($shift) {
            // Ambil status dari JSON attendance_data, default 'tidak_hadir'
            $rawStatus = $shift->attendance_data[$employee->id] ?? 'tidak_hadir';

            // Siapkan properti tambahan untuk View
            $employee->attendance_status_raw = $rawStatus; // Untuk logika warna (hadir/tidak_hadir)
            $employee->attendance_label = str_replace('_', ' ', strtoupper($rawStatus)); // Untuk teks label (HADIR/TIDAK HADIR)
            
            return $employee;
        });

        return view('admin.shifts.show', [
            'shift' => $shift,
            'employees' => $employees,
            'title' => 'Detail Rekap Absensi'
        ]);
    }

    public function create(){
        $branches = Branch::all();
        return view('admin.shifts.create', compact('branches'));
    }

    public function attendance(Request $request){
        $request->validate([
            'branch_id' => 'required',
            'shift_day' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
        $branch = Branch::findOrFail($request->branch_id);
        $employees = Employee::where('branch_id', $branch->id)->where('is_active', true)->get();
        return view('admin.shifts.attendance', [
            'employees' => $employees,
            'branch' => $branch,
            'shift_day' => $request->shift_day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'branch_id' => 'required',
            'shift_day' => 'required',
            'attendance_data' => 'required|array'
        ]);
        Shift::create($request->all());
        return redirect()->route('admin.shifts.index')->with('success', 'Rekapan shift berhasil disimpan.');
    }

    public function edit($id){
        $shift = Shift::with('branch')->findOrFail($id);
        $employees = \App\Models\Employee::where('branch_id', $shift->branch_id)->get();

        $employees->transform(function ($employee) use ($shift) {
            $currentStatus = $shift->attendance_data[$employee->id] ?? 'tidak_hadir';
            $employee->edit_status_value = $currentStatus;
            $employee->edit_is_present = ($currentStatus === 'hadir');
            return $employee;
        });

        return view('admin.shifts.edit', [
            'title' => 'Edit Absensi',
            'shift' => $shift,
            'employees' => $employees
        ]);
    }

    public function update(Request $request, $id){
        $shift = Shift::findOrFail($id);
        $request->validate([
            'attendance_data' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $shift->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'attendance_data' => $request->attendance_data, // Simpan array langsung (casting JSON di model)
        ]);

        return redirect()->route('admin.shifts.show', $shift->id)
            ->with('success', 'Data absensi berhasil diperbarui.');
    }
}