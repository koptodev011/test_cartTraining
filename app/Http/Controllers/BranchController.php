<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Attendance;
use App\Models\Branch;

use Validator;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use Carbon\Carbon;
class BranchController extends Controller
{
    public function branch(Request $request)
    {
        $branch = Branch::all();
        // $videos = Video::whereIn('role', [1])->get();
        return view('branch', compact('branch'));
    }

    


    public function createbranch(Request $request){

       
        $validator = Validator::make($request->all(), [
            'branch_name' => 'required|string|max:255',
            'branch_address' =>'nullable|string',
        ]);     
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
       
        $branch = new Branch(); 
        $branch->branch_name = $request->input('branch_name');
        $branch->branch_address=$request->input('branch_address');
        $branch->save();
        return redirect()->route('branch')->with('success', 'User updated successfully.');
    
    }

    // Send data to the employee page
    



}
