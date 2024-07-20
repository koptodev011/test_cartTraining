<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\Feedback;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Allocatedstdent extends Controller
{
    public function Allocatedstdents(Request $request)
    {
        $user = $request->user();
        $allocatedStudentIds = Feedback::where('trainer_id', $user->id)->pluck('user_id')->toArray();
      if(!$allocatedStudentIds){
        return response()->json(['Message' => "Manager is not Allocate Student to You.."]);
      }
        $allocatedStudents = [];
        foreach ($allocatedStudentIds as $userId) {
            $allocatedStudent = User::find($userId);
            if ($allocatedStudent) {
                $allocatedStudents[] = $allocatedStudent;
            }
        }
        return response()->json(['allocatedStudents' => $allocatedStudents]);
    }

    

    public function Studentdetails(Request $request){
     
        $Studentdetails = User::find($request->id);

        return response()->json(['studentdetails' =>  $Studentdetails]);
    }

    // public function getStudentDetails(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|integer',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }
    //     $student = User::find($request->id);
    //     if (!$student) {
    //         return response()->json(['error' => 'Student not found'], 404);
    //     }
    //     return response()->json(['student' => $student]);
    // }
    


    public function feedback(Request $request)
    {
        $userId = $request->id;
        $feedbackText = $request->feedback;
        $batch = $request->batch;
    
        $currentDate = now()->toDateString(); // Get the current date
   
        $updated = Feedback::where('user_id', $userId)
                           ->where('batch', $batch)
                           ->whereDate('created_at', $currentDate) // Additional condition for current date
                           ->update(['feedback' => $feedbackText]);
    
        if ($updated) {
            return response()->json(['message' => 'Feedback updated successfully']);
        } else {
            return response()->json(['message' => 'Student feedback not found for today'], 404);
        }
    }
    
    
    
}
