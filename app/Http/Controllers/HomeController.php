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
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

 
    public function employee(Request $request)
    {
        $student = User::all();
        return view('student', compact('student'));
    }
   


    public function studentForm(){
        return view('studentForm');
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
       
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email',
            'user_address' =>'nullable|string',
            'user_phone' => 'nullable|string|max:10',
            'user_password' => 'required|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
     
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

    
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
        $user->is_delete = 0;
       
        $user->save();
    

    }


  


    public function getUsers()
    {
        $users = User::all();
        return view('users', compact('users'));
        
    }


    // public function student(){
    //     $student = User::all();
    //     return view('student', compact('student'));
    // }


      public function student(){
        $student = User::whereIn('role', [1])->get();
        $updatedEmployees = $student->map(function ($students) {
            if ($students->role == 1) {
                $students->role = 'Student';
            }
            if($students->shift == 1){
                $students->shift = "8:00 To 12:00";
            }else{
                $students->shift = "1:00 To 4:00";
            }
            return $students;
        });
    
        return view('student', compact('student'));
    }
    
   
   
    
    public function studenttest(){
        return view('studenttest');
    }

    // public function edit(){
    //     $student = User::all();
    //     return view('Edit',compact('student'));
    // }

    public function edit($id)
    {
        echo($id);
        dd();
        // Fetch the user with the given ID from the database
        $user = User::findOrFail($id);
    
        // Return a view for editing the user (you can customize this as needed)
        return view('users.edit', ['user' => $user]);
    }
    


    public function updatestudent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email,' . $id,
            'user_phone' => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Find the user by ID
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    

        // Update user details
        $user->name = $request->input('user_name');
        $user->email = $request->input('user_email');
        $user->phone = $request->input('user_phone');

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');
            $profilePhotoName = time() . '_' . $profilePhoto->getClientOriginalName();
            $profilePhoto->move(public_path('profile_photos'), $profilePhotoName);
            $user->profile_photo_path = 'profile_photos/' . $profilePhotoName;
        }

        // Save user record
        $user->save();

        // Optionally, redirect or return response
        return redirect()->back()->with('success', 'User updated successfully');
      
    }


    public function delete($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }
        User::where('id', $id)->update(['is_delete' => 1]);
       
    }


    public function generatePDF() {
        $users = User::get();
        $data = [
            'title' => 'Welcome to the code',
            'users' => $users
        ];
        $pdf = PDF::loadView('myPDF', $data); // Assuming PDF is the class for PDF generation
        dd();
        return $pdf->download('Student.pdf');
    }



    // public function createEmployee(Request $request)
    // {
    //     // Handle your logic here
    //     echo "Working";  // Example output
    //     dd($request->all());  // Example to dump all input data
    // }



   
    
    

}
