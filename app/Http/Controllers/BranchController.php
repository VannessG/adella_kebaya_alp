<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller{
    public function select(){
        $branches = Branch::where('is_active', true)->get();
        
        if ($branches->count() === 1) {
            session(['selected_branch' => $branches->first()]);
            return redirect()->route('home');
        }
        
        return view('branch.select', [
            'title' => 'Pilih Cabang',
            'branches' => $branches
        ]);
    }
    
    public function change(Request $request){
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);
        
        $branch = Branch::findOrFail($request->branch_id);
        session(['selected_branch' => $branch]);
        return redirect()->back();
    }

    public function storeSelection(Request $request){
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);
        $branch = Branch::findOrFail($request->branch_id);
        session(['selected_branch' => $branch]);
        return redirect()->route('home');
    }
}