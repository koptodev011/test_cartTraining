<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Attendance;
use Validator;
use App\Models\User;
use App\Models\Plane;
use App\Models\Payment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;

class PaymentController extends Controller
{
  
   

    public function yearlyAnalysis()
    {
       
        $user = Auth::user();
        $currentYear = now()->year;
        $yearlySums = [];
        
        for ($i = 0; $i < 4; $i++) {
            $yearToQuery = $currentYear - $i;
            $yearSum = Payment::whereYear('created_at', $yearToQuery)
                ->where('branch', $user->branch)
                ->sum('amount');
              
            $yearlySums[$yearToQuery] = $yearSum;
           
        }
        // I Store all years sale sum in this  $yearlySums this array
        $currentYearSum = $yearlySums[$currentYear];
        $percentageChanges = [];
        
        foreach ($yearlySums as $year => $sum) {
            if ($year != $currentYear) {
                $percentage = (($currentYearSum - $sum) / $sum) * 100;
                $roundedPercentage = ceil($percentage);
                $percentageChanges[$year] = $roundedPercentage;
            } else {
                $percentageChanges[$year] = 0;
            }
        }
       // I Store all years sale sum in this $percentageChanges this array
        
        $years = array_keys($percentageChanges);
        $percentages = array_values($percentageChanges); 
     
        return [$years, $percentages];
        
    }
    
    
    public function branchAnalysis()
    {
        $branches = Branch::all();
        $percentageChanges = [];
    
        foreach ($branches as $branch) {
            $branchId = $branch->id;
            $currentYear = now()->year;
            
            $currentYearSum = Payment::where('branch', $branchId)
                ->whereYear('created_at', $currentYear)
                ->sum('amount');
    
            $previousYearSum = Payment::where('branch', $branchId)
                ->whereYear('created_at', $currentYear - 1)
                ->sum('amount');
                // echo("CorrentYearSum $currentYearSum");
                // echo("previousYearSum $previousYearSum");
    
            $percentageChange = 0;
            if ($previousYearSum != 0) {
                $percentageChange = (($currentYearSum - $previousYearSum) / $previousYearSum) * 100;
            }
            
            $percentageChanges[$branch->branch_name] = number_format($percentageChange, 2);
        }
    
        // Prepare data for the view
        $branchNames = array_keys($percentageChanges);
        $percentages = array_values($percentageChanges);
    
        return view('home', [
            'branchNames' => $branchNames,
            'percentages' => $percentages,
        ]);
    }


    

    public function planAnalysis() {
        $user = Auth::user();
        $plans = Plane::all();
        $currentYear = now()->year;
        $degrees = [];
        
        if ($user->role == 4) {
            $yearSum = Payment::whereYear('created_at', $currentYear)->sum('amount');
            foreach ($plans as $plan) {
                $id = $plan->id;
                $planSum = Payment::whereYear('created_at', $currentYear)
                                  ->where('plan_id', $id)
                                  ->sum('amount');
                
                $percentage = ($planSum / $yearSum) * 100;
                $degree = ($percentage / 100) * 360;
                $degrees[] = [
                    'id' => $id,
                    'name' => $plan->name, 
                    'degree' => $degree
                ];
            }
        } else {
            $yearSum = Payment::whereYear('created_at', $currentYear)
                              ->where('branch', $user->branch)
                              ->sum('amount');
                              
            foreach ($plans as $plan) {
                $id = $plan->id;
                $planSum = Payment::whereYear('created_at', $currentYear)
                                  ->where('branch', $user->branch)
                                  ->where('plan_id', $id)
                                  ->sum('amount');
            
             
                $percentage = ($planSum / $yearSum) * 100;
                $degree = ($percentage / 100) * 360;
    
                $degrees[] = [
                    'id' => $id,
                    'name' => $plan->name, 
                    'degree' => $degree
                ];
                
            }
          
        }
        return [$degrees];
    }


    public function monthlyAnalysis()
    {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;
        $monthlyData = [];
        $totalYearlySum = 0;

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
        // Calculate total amount for the entire year
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            $sum = Payment::where('branch', $user->branch)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
    
            $totalYearlySum += $sum;
    
            // Only include months with positive sum
            if ($sum > 0) {
                $monthlyData[] = [
                    'month' => $months[$month - 1], // Adjust month index to match array (0-based index)
                    'amount' => $sum,
                ];
            }
        }
    
        // Calculate percentages if totalYearlySum is greater than 0
        if ($totalYearlySum > 0) {
            foreach ($monthlyData as &$data) {
                $percentage = ($data['amount'] / $totalYearlySum) * 100;
                $data['percentage'] = $percentage;
            }
        }
    
        return $monthlyData;
        // return view('home', compact('monthlyData'));
    }
    
    




    public function dashborddata()
    {
        $user = Auth::user();
        $currentYear = now()->year;
        $branch = Branch::where('id', $user->branch)->first();
        $plans = Plane::all();
        $yearSums = [];
        for ($i = 0; $i < 2; $i++) {
            $currentYearSum = Payment::where('branch', $user->branch)
                ->whereYear('created_at', $currentYear)
                ->sum('amount');

            $yearSums[$currentYear] = $currentYearSum;
            $currentYear--;
        }
    
        $currentYearSum = isset($yearSums[date('Y')]) ? $yearSums[date('Y')] : 0;
        $previousYearSum = isset($yearSums[date('Y') - 1]) ? $yearSums[date('Y') - 1] : 0;
        $growthrate = ($previousYearSum != 0) ? (($currentYearSum - $previousYearSum) / $previousYearSum) * 100 : 0;
    
        list($years, $percentages) = $this->yearlyAnalysis();
        list($degrees) = $this->planAnalysis();
        $monthlyData = $this->monthlyAnalysis();

        return view('home', [
            'growthrate' => $growthrate,
            'currentYearSum' => $currentYearSum,
            'plan' => count($plans),
            'branch' => $branch->branch_name,
            'user' => $user,
        ],compact('years', 'percentages','degrees','monthlyData'));
    }






    }
