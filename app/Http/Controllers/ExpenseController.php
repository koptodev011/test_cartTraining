<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PDF;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class ExpenseController extends Controller
{
    public function Expense(){
        return view('/expensemanagement');
        // compact('student','branch','plans')
    }


    public function Expensedata()
    {
        // $expenses = Expense::all();
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

$expenses = Expense::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->get();
                    
        $findTearinerIds = [];
        foreach ($expenses as $expense) {
            if (!in_array($expense->trainer_id, $findTearinerIds)) {
                $findTearinerIds[] = $expense->trainer_id;
            }
        }

        $expensesSum = [];

        foreach ($findTearinerIds as $trainerId) {
            $sum = 0;
            foreach ($expenses as $expense) {
                if ($expense->trainer_id == $trainerId) {
                    $sum += $expense->expense_amount;
                }
            }
            $trainerInfo = User::select('name', 'phone')
                               ->where('id', $trainerId)
                               ->first();
            $expensesSum[] = [
                'trainer_id' => $trainerId,
                'trainer_name' => $trainerInfo->name,
                'trainer_mobile_number' => $trainerInfo->phone,
                'total_expense_amount' => $sum
            ];
        }
        return view('expensemanagement', ['expensesSum' => $expensesSum]);
    }
    


    
    public function viewExpense($trainer_id)
    { 
        $currentYear = now()->year;
        $currentMonth = now()->month;
    
        $expenses = Expense::whereYear('created_at', $currentYear)
                           ->whereMonth('created_at', $currentMonth)
                           ->where('trainer_id', $trainer_id)
                           ->get();
    
        return view('viewexpense', compact('expenses'));
    }


    
    



    
}
