<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use App\Models\Plane;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Feedback;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class FeedbackController extends Controller
{
    public function TrainerFeedback(Request $request){
        $validator = Validator::make($request->all(), [
            'Trainer_feedback' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
    
        $trainer_feedback = $request->input('Trainer_feedback');
        $user = $request->user();
        $currentDate = Carbon::now()->toDateString();
    
        $affectedRows = Feedback::whereDate('created_at', $currentDate)
            ->where('batch', $user->shift)
            ->where('user_id', $user->id)
            ->update(['trainer_feedback' => $trainer_feedback]);
    
        if ($affectedRows > 0) {
            return response()->json(['message' => 'Trainer feedback updated successfully']);
        } else {
            return response()->json(['message' => 'No feedback records updated']);
        }
    }


   
}

