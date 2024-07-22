<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plane;
use App\Models\Car;

use Validator;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;

class PlaneManagementController extends Controller
{
    // public function planeManagement(Request $request){
    //     // $plane = Plane::all();
    //     $plane = Plane::where('active', 0)->get();
    //     $deactivated=Plane::where('active', 1)->get();
    //     $showBatchColumn = true;
    //     return view('planemanagement', compact('plane','deactivated','showBatchColumn'));
    // }


    public function planeManagement(Request $request)
{
    $plane = Plane::where('active', 0)->get();
    $deactivated = Plane::where('active', 1)->get();
    $showBatchColumn = true; // Assuming you want to show the Batch column
    return view('planemanagement', compact('plane', 'deactivated', 'showBatchColumn'));
}


   

    public function addPlaneDetails(Request $request){
       
        $validator = Validator::make($request->all(), [
            'plane_name' => 'required|string|max:255',
            'plane_fees' => 'required',
            'plane_duration' =>'nullable|string',  
            'plane_description' => 'required',
        ]);   
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $plane = new Plane(); 
        $plane->plane_name = $request->input('plane_name');
        $plane->plane_fees = $request->input('plane_fees');
        $plane->plane_duration = $request->input('plane_duration');
        $plane->plane_description = $request->input('plane_description');
        
        $plane->save();

        return redirect()->route('plane-management')->with('success', 'User updated successfully.');
    }





    public function editplanDetails(Request $request)
    {
        $id = $request->id;
        $editplandetails = Plane::find($id);
        return view('editplan', compact('editplandetails'));
    }


    












    public function updateplandetails(Request $request, $id)
    {
        $id = $request->id;
        $plan = Plane::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'plan-name' => 'required|string|max:255',
            'plan-fees' => 'required',
            'plan-duration' => 'nullable|string',
            'plan-description' => 'required',
        ]);

      
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $name = $request->input('plan-name');
        $fees = $request->input('plan-fees');
        $duration = $request->input('plan-duration');
        $description= $request->input('plan-description');

        $plan->plane_name = $name;
        $plan->plane_fees = $fees;
        $plan->plane_duration = $duration;
        $plan->plane_description=$description;

        $plan->save();
        return redirect()->route('planeManagement')->with('success', 'User updated successfully.');
    }








    public function submitForm123(Request $request)
    {
        $planeId = $request->input('plane_id');
        $deactivated=Plane::where('active', 1)->get();
       
        $affectedRows = Plane::where('id', $planeId)
                             ->update(['active' => 0]);
        return redirect()->route('planeManagement')->with('success', 'User updated successfully.');
    }
  


    public function deactivateplan($id)
    {
        $plan = Plane::find($id);
        if (!$plan) {
            return redirect()->back()->with('error', 'User not found');
        }
        Plane::where('id', $id)->update(['active' => 1]);
        return redirect()->route('planeManagement')->with('success', 'User updated successfully.');
    }

   
    public function allCourseDetails(Request $request)
    {
        $planes = Plane::where('active', 0)->get();
        return response()->json([
            'planes' => $planes,
        ]);
    }



    public function UpgradeCourse(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string',
            'registration_no' => 'required|string',
            'email' => 'required|email',
            'plan_id' => 'required|integer',
        ]);
    
        try {
            $user = $request->user();
            if ($user->registration_no !== $validatedData['registration_no'] || $user->email !== $validatedData['email']) {
                return response()->json(['error' => 'Registration number or email does not match.'], 400);
            }
    
            $user->update(['plan_id' => $validatedData['plan_id']]);
            
            return response()->json(['message' => 'Plan upgraded successfully']);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to upgrade plan. Please try again.'], 500);
        }
    }
    

    
}
