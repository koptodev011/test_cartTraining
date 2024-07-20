<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function profile(Request $request)
    {
       
        return JsonResource::make([
            'user' => $request->user(),
        ]);
    }

    // public function editProfile(Request $request)
    // {
    //     $id = $request->user()->id;
    //     $user = User::find($id);

    //     $request->validate([
    //        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
    //        'mobile_number' => ['required', 'min:10'],
    //        'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
    //     ]);

        
        // if ($request->hasFile('profile_picture')) {
        //     if ($user->profile_picture) {
        //         Storage::delete($user->profile_picture);
        //     }

        //     $profilePicture = $request->file('profile_picture');
        //     $originalFilename = $profilePicture->getClientOriginalName();
        //     $fileExtension = $profilePicture->getClientOriginalExtension();


        //     $publicPath = 'profile_pictures/' . str_replace(" ", "", time() . "_" . md5($originalFilename) . "." . $fileExtension);
           
        //     Storage::disk('public')->put($publicPath, file_get_contents($profilePicture));
        //     $fullUrl = Storage::disk('public')->url($publicPath);

        //     $finalUrl = str_replace('storage', 'public/storage', $fullUrl);
        //     $user->profile_picture = $finalUrl;
        //     $user->update();
        // }
        // if($request->hasFile('profile_picture')) {
        //     $image_name = str()->uuid() . '.' . $request->profile_picture->getClientOriginalExtension();
        //     $path = $request->profile_picture->storeAs('profile_pictures', $image_name,'public');

        //     $validatedData['profile_picture'] = $path;

        //     $user->update($validatedData);
        // }
      
        // $validatedData['email'] = $request->email;
        // $validatedData['mobile_number'] = $request->mobile_number;
       
        // $user->update($validatedData);
        // $profile_picture = storage_path();

        // return JsonResource::make([
        //     'user' => $user,
        // ]);
    // }


    public function editProfile(Request $request) {
        $id = $request->user()->id;
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'mobile_number' => ['required', 'string', 'min:10'],
            'profile_photo' => 'image|mimes:jpeg,png,jpg',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user = User::findOrFail($id);
    
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $profilePhoto = $request->file('profile_photo');
            $profilePhotoName = time() . '_' . $profilePhoto->getClientOriginalName();
            $profilePhoto->move(public_path('profile_photos'), $profilePhotoName);
            $profilePhotoPath = 'profile_photos/' . $profilePhotoName;
    
            $user->profile_photo_path = $profilePhotoPath;
        }
        $user->update([
            'email' => $request->input('email'),
            'phone' => $request->input('mobile_number'),
        ]);
    
        return response()->json(['message' => 'Profile updated successfully'], 200);
    }
    
    public function setFcm(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);
        $token = $request->token;
        $userId = Auth::user()->id;

        $user = User::find($userId);
        $user->update([
            'fcm' => $token
        ]);
        
        return response()->json(['status' => 200]);
    }
    
}
