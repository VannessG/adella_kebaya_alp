<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Branch;
use Illuminate\Http\Request;

class EmployeeController extends Controller{
    public function index(){
        $query = Employee::with('branch')->latest();

        if (session()->has('selected_branch')) {
            $branch = session('selected_branch');
            $query->where('branch_id', $branch->id);
        }
        elseif (session()->has('branch_id')) {
            $query->where('branch_id', session('branch_id'));
        }
        $employees = $query->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create(){
        $branches = Branch::all();
        return view('admin.employees.create', compact('branches'));
    }

    public function store(Request $request){
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'nik' => 'required|unique:employees,nik',
            'address' => 'required',
            'phone' => 'required',
        ]);

        Employee::create($request->all());
        return redirect()->route('admin.employees.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Employee $employee){
        $branches = Branch::all();
        return view('admin.employees.edit', compact('employee', 'branches'));
    }

    public function update(Request $request, Employee $employee){
        $request->validate([
            'name' => 'required', 
            'nik' => 'required|unique:employees,nik,'.$employee->id
        ]);
        
        $employee->update($request->all());
        return redirect()->route('admin.employees.index')->with('success', 'Data pegawai diperbarui.');
    }

    public function destroy(Employee $employee){
        $employee->delete();
        return back()->with('success', 'Pegawai berhasil dihapus.');
    }
}