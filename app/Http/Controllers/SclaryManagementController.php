<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Attendance;
use Validator;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class SclaryManagementController extends Controller
{



public function sclaryManagement()
{
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;
   

    $startDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth();
    $endDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth();

    $daysExcludingSundays = 0;
    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
        if ($date->dayOfWeek != Carbon::SUNDAY) {
            $daysExcludingSundays++;
        }
    }

    $sclarydata = User::with(['attendance' => function ($query) use ($currentMonth, $currentYear) {
        $query->whereMonth('created_at', $currentMonth)
              ->whereYear('created_at', $currentYear);
    }])->get();

    $sclaryDataArray = [];

    foreach ($sclarydata as $user) {
        $attendanceCount = $user->attendance->count();
        $sclary = ($user->sclary / $daysExcludingSundays) * $attendanceCount;
        
        // Round up the value of $sclary
        $sclary = ceil($sclary);

        $sclaryDataArray[] = [
            'user' => $user,
            'attendanceCount' => $attendanceCount,
            'sclary' => $sclary,
        ];
    }

    // You can pass $sclaryDataArray, $sclary, and $sclarydata to the view
    return view('sclarymanagement', compact('sclaryDataArray', 'sclary', 'sclarydata'));
}










public function searchRecords(Request $request){
 
    $validator = Validator::make($request->all(), [
        'month' => 'required|string|max:255',
        'year' => 'required|email|unique:users,email',
       
    ]); 
   
    $currentMonth=$request->input('month');
    $currentYear=$request->input('year');

    //This code is used to find duration of the month and excluding sundays
    $startDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth();
    $endDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth();
 
    $daysExcludingSundays = 0;
    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
        if ($date->dayOfWeek != Carbon::SUNDAY) {
            $daysExcludingSundays++;
        }
    }

    $sclarydata = User::with(['attendance' => function ($query) use ($currentMonth, $currentYear) {
        $query->whereMonth('created_at', $currentMonth)
              ->whereYear('created_at', $currentYear);
    }])->get();


    //This is used to fetchout data form the upperquery
        foreach ($sclarydata as $user) {
        $attendanceCount = $user->attendance->count();
        $sclary = ($user->sclary / $daysExcludingSundays) * $attendanceCount;
            $sclary = ceil($sclary);

        $sclaryDataArray[] = [
            'user' => $user,
            'attendanceCount' => $attendanceCount,
            'sclary' => $sclary,
        ];


    }

    return view('sclarymanagement', compact('sclaryDataArray', 'sclary', 'sclarydata'));
}



}