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

class StudentController extends Controller
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

    public function submitForm(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_address' =>'nullable|string',
            'user_phone' => 'nullable|string|max:10',
            'user_password' => 'required|string',
            'user_shift' => 'nullable',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
     
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        // $id = $request->user_shift;
        // echo($id);
        // dd();
    
        // Generate random ID
        $randomId = $this->generateRandomId();
    
      
        // Handle profile photo upload
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
        $user->role = 1;
        $user->registration_no=$randomId;
        $user->shift=$request->input('user_shift');
        $user->is_delete = 0;
       
        $user->save();
    

    }



    
public function edit($user)
{
    $user = User::find($user);
    return view('edit-user', ['user' => $user]);
}


public function editstudent(Request $request)
{
    $id = $request->id;
    $employee = User::find($id);
    return view('editstudent', compact('employee'));
}


public function updatestudent(Request $request, $id)
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
   

    return redirect()->route('student')->with('success', 'User updated successfully.');
}

public function deletestudent($id)
{
    $user = User::find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }
    User::where('id', $id)->update(['is_delete' => 1]);
    return redirect()->route('student')->with('success', 'User updated successfully.');
   
}




}
