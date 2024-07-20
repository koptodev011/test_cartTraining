<?php

namespace App\Http\Controllers\Api;

use App\Helpers\TokenCodeHelper;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordRequested;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    //
    public function login(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
          
        ]);

       
        $user = User::where('email', $request->email)
        ->where(function ($query) {
            $query->whereNull('is_delete')
                ->orWhere('is_delete', 0);
        })
        ->first();


        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect Or your account is deleted.'],
            ]);
        }
        

       
        $token = $user->createToken($request->email)->plainTextToken;
      
        return [
            'data' => $token
        ];

    }

    public function forgotPassword(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
        ]);

        // $user = User::where('email', $attributes['email'])->first();
         $user = User::where('email', $attributes['email'])
            ->where(function ($query) {
                $query->whereNull('is_delete')
                    ->orWhere('is_delete', 0);
            })
            ->first();
            if($user){
                $tokenCode = TokenCodeHelper::newCode();

                $passwordReset = PasswordReset::where('email', $attributes['email'])->first();
                if($passwordReset != null) {
                    $passwordReset->update([
                        'token' => $tokenCode
                    ]);
                } else {
                    $passwordReset = PasswordReset::create([
                        'email' => $attributes['email'],
                        'token' => $tokenCode
                    ]);
                }
        
        
                Mail::to($user)->send(new ForgotPasswordRequested($passwordReset));
        
                return new JsonResource([
                    'message' => 'A code has been sent to your email address'
                ],200);
            }else{
                return new JsonResource([
                    'message' => 'Invalid email or deleted account'
                ],400);
            }
       

    }

    public function verifyOtp(Request $request)
    {
        $attributes = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',

        ]);        

        $user = User::where('email', $attributes['email']) ->where(function ($query) {
            $query->whereNull('is_delete')
                ->orWhere('is_delete', 0);
        })->first();
        $passwordReset = PasswordReset::where('email', $user->email)->first();

        if($passwordReset == null) {
            return response()->json([
                'message' => 'Otp mismatch'
            ], 400);
        }

        if($passwordReset->token != $attributes['otp']) {

            return response()->json([
                'message' => 'Otp mismatch'
            ], 400);
        }

     
        return response()->json([
            'status' => 200,
            'message'=> 'otp verified'           
        ]);
    }


    public function resetPassword(Request $request)
    {

        $attributes = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
            'password' => ['required', 'min:6', 'confirmed']
        ]);


        $user = User::where('email', $attributes['email'])->first();

        $user->fill([
            'password' => Hash::make($attributes['password'])
        ]);

        $user->save();

        $user->tokens()->delete();

        return JsonResource::make([
            'message' => 'Password has been changed. Please Relogin'
        ]);

    }

    public function changePassword(Request $request)
    {

        $attributes = $request->validate([
            'old_password' => ['required', 'min:6'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        if($attributes['old_password'] == $attributes['password']) {
            return response()->json([
                'message' => 'New password should be different than old password'
            ], 400);
        }

        $user = $request->user();
        if(!Hash::check($attributes['old_password'], $user->password)) {

            return response()->json([
                'message' => 'Credentials are not valid'
            ], 400);

        }

        $user->fill([
            'password' => $attributes['password']
        ]);
        $user->save();

        $user->tokens()->delete();

        return JsonResource::make([
            'message' => 'Password has been changed. Please Relogin'
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'data' => 'success'
        ];
    }

    public function deleteAccount(){
        $user_id=Auth::user()->id;
        $user=User::find($user_id);
      
        $user->update([
            'is_delete' => 0
        ]);
        return response()->json([
            'status' => 200,
            'message'=> 'Account deleted successfully'           
        ]);
    }

}
