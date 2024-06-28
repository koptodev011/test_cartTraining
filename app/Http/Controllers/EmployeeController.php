<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;

class EmployeeController extends Controller
{


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
    


    public function createEmployee(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_address' =>'nullable|string',
            'user_phone' => 'nullable|string|max:10',
            'user_password' => 'required|string',
            'user_role' => 'nullable',
            'user_shift' => 'nullable',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);     
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $randomId = $this->generateRandomId();
        $profilePhotoPath = null;
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');
            $profilePhotoName = time() . '_' . $profilePhoto->getClientOriginalName();
            $profilePhoto->move(public_path('profile_photos'), $profilePhotoName);
            $profilePhotoPath = 'profile_photos/' . $profilePhotoName;
        }
        $user = new User(); 
        $user->name = $request->input('user_name');
        $user->email = $request->input('user_email');
        $user->phone = $request->input('user_phone');
        $user->address=$request->input('user_address');
        $user->password = Hash::make($request->input('user_password'));
        $user->profile_photo_path = $profilePhotoPath;
        $user->role = $request->input('user_role');
        $user->registration_no=$randomId;
        $user->shift=$request->input('user_shift');
        $user->is_delete = 0;
        $user->save();
    
    }



    // public function employee(Request $request)
    // {
    //     $student = User::all();
    //     return view('employee', compact('student'));
    // }


//     public function employee(Request $request)
// {
//     $employee = User::whereIn('role', [2, 3])->get();

//     return view('employee', compact('employee'));
// }

public function employee(Request $request)
{
    $employee = User::whereIn('role', [2, 3])->get();
    $updatedEmployees = $employee->map(function ($employees) {
        if ($employees->role == 2) {
            $employees->role = 'Trainer';
        }
        if($employees->role == 3){
            $employees->role = 'Receptionist';
        }
        if($employees->shift == 1){
            $employees->shift = "8:00 To 12:00";
        }else{
            $employees->shift = "1:00 To 4:00";
        }
        return $employees;
    });


    return view('employee', compact('employee'));
}




public function edit($user)
{
    $user = User::find($user);
    return view('edit-user', ['user' => $user]);
}


public function editemployee(Request $request)
{
    $id = $request->id;
    $employee = User::find($id);
    return view('editemployee', compact('employee'));
}


public function updateemployee(Request $request, $id)
{

   
    $id = $request->id;
 
    $employee = User::findOrFail($id);

    // Validate the form data
    $request->validate([
        'user_name' => 'required|string',
        'user_email' => 'required|email',
        'user_address' => 'required|string',
        'user_phone' => 'required|string',
        'user_shift' => 'required|in:1,2',
        'profile_photo' => 'image|mimes:jpeg,png,jpg|max:2048', // Validate image file types and size
    ]);

    // Update user data
    $employee->name = $request->user_name;
    $employee->email = $request->user_email;
    $employee->address = $request->user_address;
    $employee->phone = $request->user_phone;
    $employee->shift = $request->user_shift;

    // Handle profile photo upload if a new file is uploaded
    if ($request->hasFile('profile_photo')) {
        // Delete old profile photo if exists
        if ($employee->profile_photo_path) {
            Storage::delete($employee->profile_photo_path);
        }

        // Store new profile photo
        $imagePath = $request->file('profile_photo')->store('avatars', 'public');
        $employee->profile_photo_path = $imagePath;
    }
    $employee->save();
   

    return redirect()->route('employee')->with('success', 'User updated successfully.');
}

public function delete($id)
{
    $user = User::find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }
    User::where('id', $id)->update(['is_delete' => 1]);
    return redirect()->route('employee')->with('success', 'User updated successfully.');
   
}

}