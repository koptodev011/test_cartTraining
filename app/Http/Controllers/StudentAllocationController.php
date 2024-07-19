<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
class StudentAllocationController extends Controller
{
    

    public function Allocatestudent(Request $request){
        $trainer_id=$request->id;
        $Allstudentdata = User::all();
        return view('allocatestudents', compact('Allstudentdata','trainer_id'));
    }


       public function PresentTeachers() {
        $user = Auth::user();
    
        $today = Carbon::now()->toDateString();
        $presentTrainer = Attendance::where('branch', $user->branch)
            ->where('role', 2)
            ->whereDate('created_at', $today)
            ->get();
            $Allstudentdata = User::all();
            return view('allocatestudents', compact('presentTrainer'));
    }

    public function Allocate(Request $request){
        $user = Auth::user();
        $feedback = new Feedback(); 
        $feedback->user_id = $request->id;
        $feedback->trainer_id = $request->trainer_id;
        $feedback->save();
        echo("saved");
    }


}
