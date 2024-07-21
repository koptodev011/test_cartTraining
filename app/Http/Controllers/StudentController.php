<?php

namespace App\Http\Controllers;

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
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

use PDF;

class StudentController extends Controller
{
    public function student(){
        $user = Auth::user();
        
        $branch = Branch::all();
        $plans = Plane::where('active', 0)->get();
        
        $deletedstudentlist = User::where('branch',  $user->branch)
        ->where('is_delete', 1)
        ->where('role', 1)
        ->get();

        if($user->role==3){
            $student = User::where('branch',  $user->branch)
            ->where('role', 1)
            ->where('is_delete', 0)
            ->get();
        }else{
           $student = User::where('role', 1)->get();
        }
        $updatedStudents = $student->map(function ($student) {
            $student->role = ($student->role == 1) ? 'Student' : $student->role;
            $student->shift = ($student->shift == 1) ? '8:00 To 12:00' : '1:00 To 4:00';
            return $student;
        });
        return view('student', compact('student','branch','plans','deletedstudentlist'));
    }




     

    public function generateRandomId()
    {
        $length = 8;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

   

    public function submitForm(Request $request)
{
    $user = new User();
    $plan = Plane::find($request->input('user_plan'));
    $authorizedUser = Auth::user();
    $payment = new Payment();

    $validator = Validator::make($request->all(), [
        'user_name' => 'required|string|max:255',
        'user_email' => 'required|email|unique:users,email',
        'user_address' => 'required|string',
        'user_phone' => 'required|string|max:10',
        'user_password' => 'required|string',
        'user_shift' => 'required',
        'user_branch' => 'nullable',
        'user_plan' => 'nullable|exists:planes,id', // Ensure user_plan exists in planes table
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    if ($validator->fails()) {
        Alert::error('Validation Error', 'Some fields are empty')
             ->showConfirmButton('Okay');
        return redirect()->back()->withErrors($validator)->withInput();
    }
    

    $profilePhotoPath = null;
    if ($request->hasFile('profile_photo')) {
        $profilePhoto = $request->file('profile_photo');
        $profilePhotoName = time() . '_' . $profilePhoto->getClientOriginalName();
        $profilePhoto->move(public_path('profile_photos'), $profilePhotoName);
        $profilePhotoPath = 'profile_photos/' . $profilePhotoName;
    }

    
    if ($authorizedUser && $authorizedUser->role == 3) {
        $user->branch = $authorizedUser->branch;
        $payment->branch = $authorizedUser->branch;
    } else {
        $user->branch = $request->input('user_branch');
        $payment->branch = $request->input('user_branch');
    }

    $user->name = $request->input('user_name');
    $user->email = $request->input('user_email');
    $user->phone = $request->input('user_phone');
    $user->address = $request->input('user_address');
    $user->password = Hash::make($request->input('user_password'));
    $user->profile_photo_path = $profilePhotoPath;
    $user->role = 1;
    $user->shift = $request->input('user_shift');
    $user->plan_id = $request->input('user_plan');
    $user->registration_no = $this->generateRandomId();
    $user->is_delete = 0;
    $user->save();



    if ($request->input('user_plan')) {
        if ($plan) {
            $payment->plan_id = $plan->id;
            $payment->user_id = $user->id;
            $payment->paid = 1;
            $payment->payment_provider_response = "cash";
            $payment->amount = $plan->plane_fees;
            $payment->save();
        }
    }
    Alert::success('success','User registered Successifully');
    return redirect()->route('student');
}










public function editstudent(Request $request)
{
    $id = $request->id;
    $student = User::find($id);
    return view('editstudent', compact('student'));
}














public function updatestudent(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_name' => 'required|string',
        'user_email' => 'required|email|unique:users,email,' . $request->id,
        'user_address' => 'required|string',
        'user_phone' => 'required|string',
        'user_shift' => 'required|in:1,2',
        'profile_photo' => 'image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
        // SweetAlert for validation errors
        alert()->error('Validation Error', 'Please fill out all required fields correctly.')
            ->showConfirmButton('Okay');

        return redirect()->back()->withErrors($validator)->withInput();
    }

    $student = User::findOrFail($request->id);

    // Handle profile photo upload
    if ($request->hasFile('profile_photo')) {
        $profilePhoto = $request->file('profile_photo');
        $profilePhotoName = time() . '_' . $profilePhoto->getClientOriginalName();
        $profilePhoto->move(public_path('profile_photos'), $profilePhotoName);
        $profilePhotoPath = 'profile_photos/' . $profilePhotoName;

        $student->profile_photo_path = $profilePhotoPath;
    }
    $updated = $student->update([
        'name' => $request->user_name,
        'email' => $request->user_email,
        'address' => $request->user_address,
        'phone' => $request->user_phone,
        'shift' => $request->user_shift,
    ]);

    // Redirect with success or error message based on update result
    if ($updated) {
        alert()->success('Success', 'User updated successfully.');
        return redirect()->route('student')->with('success', 'User updated successfully.');
    }
}








public function deletestudent($id)
{
    $user = User::find($id);
    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }
    User::where('id', $id)->update(['is_delete' => 1]);

   
    return redirect()->route('student');
}



public function deletedstudentlist($id)
{
    $plan = Plane::find($id);
    if (!$plan) {
        return redirect()->back()->with('error', 'User not found');
    }
    Plane::where('id', $id)->update(['active' => 1]);
    return redirect()->route('planeManagement')->with('success', 'User updated successfully.');
}

public function studentAttendanse(Request $request){
    $user = $request->user();
    $attendance = new Attendance();
    $attendance->user_id=$user->id;
    $attendance->shift=$user->shift;
    $attendance->status=0;
    $attendance->save();

    return response()->json(['message' => "Attendance Added Successifully"]);
    
}


}
