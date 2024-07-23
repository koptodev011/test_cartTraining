<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;

use Validator;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;

class CarManagementController extends Controller
{
    
    public function carManagement(Request $request)
    {
        $cars = Car::where('isdelete', 1)->get();
        $deletedcars = Car::where('isdelete', 0)->get();
        return view('carmanagement', compact('cars','deletedcars'));
    }

    
    public function addCarDetails(Request $request){
        $validator = Validator::make($request->all(), [
            'car-name' => 'required|string|max:255',
            'car-number' => 'required',
            'car-fuel' =>'nullable|string',  
        ]);     
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $car = new Car(); 
        $car->car_name = $request->input('car-name');
        $car->car_number = $request->input('car-number');
        $car->fuel_type = $request->input('car-fuel');
        $car->save();
        return redirect()->route('carManagement');
    }



    public function editCarDetails(Request $request)
    {
        $id = $request->id;
        $editcardetails = Car::find($id);
        return view('editcardetails', compact('editcardetails'));
    }




    public function updatecardetails(Request $request, $id)
    {
        $id = $request->id;
        $car = Car::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'car-name' => 'required|string|max:255',
            'car-number' => 'required',
            'car-fuel' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $name = $request->input('car-name');
        $number = $request->input('car-number');
        $fuel = $request->input('car-fuel');
        $car->car_name = $name;
        $car->car_number = $number;
        $car->fuel_type = $fuel;
        $car->save();
        return redirect()->route('carManagement')->with('success', 'User updated successfully.');
    }

    // public function updatecardetails(Request $request, $id)
    // {
    //     // Validate incoming request data
    //     $validator = Validator::make($request->all(), [
    //         'car-name' => 'required|string|max:255',
    //         'car-number' => 'required',
    //         'car-fuel' => 'nullable|string',
    //     ]);
       
    //     // If validation fails, return error response
    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }
        
    //     try {
    //         // Find the car by ID
    //         $car = Car::findOrFail($id);
            
    //         // Update the car details
    //         $car->update([
    //             'car_name' => $request->input('car-name'),
    //             'car_number' => $request->input('car-number'),
    //             'fuel_type' => $request->input('car-fuel'),
    //         ]);
            
    //         // Redirect with success message
    //         return redirect()->route('carManagement')->with('success', 'Car updated successfully.');
        
    //     } catch (\Exception $e) {
    //         // Handle any exceptions (e.g., ModelNotFoundException)
    //         return redirect()->back()->with('error', 'Failed to update car details.');
    //     }
    // }





    public function deletecar($id)
    {
        $car = Car::find($id);
        if (!$car) {
            return redirect()->back()->with('error', 'User not found');
        }
        Car::where('id', $id)->update(['isdelete' => 0]);
        return redirect()->route('carManagement')->with('success', 'User updated successfully.');
    }

}