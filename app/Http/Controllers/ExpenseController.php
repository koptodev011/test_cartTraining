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





    public function addExpense(Request $request) {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'expense_title' => 'required|string',
            'expense_amount' => 'required|numeric',
            'expense_receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }
    
        $expenseReceiptPath = null;
        if ($request->hasFile('expense_receipt')) {
            $expenseReceipt = $request->file('expense_receipt');
            $expenseReceiptName = time() . '_' . $expenseReceipt->getClientOriginalName();
            $expenseReceipt->move(public_path('expense_receipt'), $expenseReceiptName);
            $expenseReceiptPath = 'expense_receipt/' . $expenseReceiptName;
        }
        
        // Create and store the expense
        $expense = new Expense();
        $expense->expense_title = $request->input('expense_title');
        $expense->expense_amount = $request->input('expense_amount');
        $expense->expense_recept = $expenseReceiptPath;
        $expense->trainer_id = $user->id;
        $expense->save();
        $videoPath = url('/' .  $expenseReceiptPath);
        return response()->json([
            'message' => 'Expense added successfully',
            'data' => [
                'expense_title' => $expense->expense_title,
                'expense_amount' => $expense->expense_amount,
                'expense_receipt' => $expense->expense_recept,
                'url'=> $videoPath,
            ],
        ], 200);
    }
    
    


    public function Fetchexpense(Request $request){
        $user = $request->user();
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

$expenses = Expense::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->where('trainer_id', $user->id)
                    ->get();

                    $Expense = [];
                    foreach ($expenses as $expense) {
                        $videoPath = url('/' . $expense->expense_recept);
                
                        $Expense[] = [
                            'expense' => $expense, // Include the entire $studentVideo object
                            'expense_path' => $videoPath,
                        ];
                    }
                    return response()->json($Expense);
    }





    public function editExpense(Request $request){
     $id=$request->id;
   
     $expense = Expense::find($id);
     if (!$expense) {
        return response()->json(['message' => 'Expense not found'], 404);
    }
    $videoPath = url('/' . $expense->expense_recept);
    return response()->json([
        'data' => [
            'expense' => $expense,
            'url'=> $videoPath,
        ],
    ], 200);
    return response()->json($expense);
}



public function Update(Request $request) {
    $user = $request->user();
    $validator = Validator::make($request->all(), [
        'id' => 'required|numeric',
        'expense_title' => 'required|string',
        'expense_amount' => 'required|numeric',
        'expense_receipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()->first(),
        ], 400);
    }
    $expense = Expense::find($request->input('id'));
    if (!$expense) {
        return response()->json([
            'error' => 'Expense not found',
        ], 404);
    }

    // Handle expense receipt update
    if ($request->hasFile('expense_receipt')) {
        $expenseReceipt = $request->file('expense_receipt');
        $expenseReceiptName = time() . '_' . $expenseReceipt->getClientOriginalName();
        $expenseReceipt->move(public_path('expense_receipt'), $expenseReceiptName);
        $expense->expense_recept = 'expense_receipt/' . $expenseReceiptName;
    }

    // Update expense fields
    $expense->expense_title = $request->input('expense_title');
    $expense->expense_amount = $request->input('expense_amount');
    
    // Use update method to save changes
    $expense->update();

    // Construct expense receipt URL
    $expenseReceiptUrl = $expense->expense_recept ? url($expense->expense_recept) : null;

    return response()->json([
        'message' => 'Expense updated successfully',
        'data' => [
            'expense_title' => $expense->expense_title,
            'expense_amount' => $expense->expense_amount,
            'expense_receipt' => $expense->expense_recept,
            'expense_receipt_url' => $expenseReceiptUrl,
        ],
    ], 200);
}


public function deleteExpense(Request $request){
    $validator = Validator::make($request->all(), [
        'id' => 'required|numeric',
    ]);
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first()], 400);
    }
    $expense = Expense::find($id);
    if (!$expense) {
        return response()->json(['message' => 'Expense not found'], 404);
    }
    $expense->delete();
    return response()->json(['message' => 'Expense deleted successfully'], 200);
}



    
}