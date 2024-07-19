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
use App\Models\Question;
use App\Models\Test;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
class TestController extends Controller
{
    public function test()
    {
        $test = Test::all();
        $deletedTests = Test::where('is_delete', 0)->get();
        return view('testmanagement', compact('test', 'deletedTests'));
    }


    public function addTest(Request $request){
        $validator = Validator::make($request->all(), [
            'test-title' =>'nullable|string',  
            'test-day' => 'required',
        ]);   
     
        if ($validator->fails()) {
            Alert::error('Validation Error', 'Some fields are empty')
                 ->showConfirmButton('Okay');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $test = new Test(); 
        $test->test_title = $request->input('test-title');
        $test->test_day = $request->input('test-day');
    
        $test->save();
        Alert::success('success','User registered Successifully');
        return redirect()->route('test');
    }


    public function editTest(Request $request)
    {
        $id = $request->id;
        $edittestdetails = Test::find($id);
        return view('edittestdetails', compact('edittestdetails'));
    }

    


    public function updatetestdetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test-title' => 'required|string',
            'test-day' => 'required|unique:tests,'. $request->id
        ]);
  
        if ($validator->fails()) {
            alert()->error('Validation Error', 'Please fill out all required fields correctly.')
                ->showConfirmButton('Okay');
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $test = Test::findOrFail($request->id);
        echo($request->id);
        dd();

        $updated = $test->update([
            'test_title' => $request->input('test-title'),
            'test_day' => $request->input('test-day'),
            
        ]);
        if ($updated) {
            alert()->success('Success', 'User updated successfully.');
            return redirect()->route('test')->with('success', 'User updated successfully.');
        }
    }
    



    public function viewquestions(Request $request){
        $id = $request->id;
        $questions = Question::where('test_id', $id)->get();
      
        $deletedQuestions = Question::where('is_delete', 0)->get();
        $viewdquestions = Test::find($id);
        return view('viewquestions', compact('questions', 'deletedQuestions','viewdquestions'));
    }

    

    public function addQuestion(Request $request) {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'question_title' => 'required|string|max:255',
            'option_1' => 'required',
            'option_2' => 'nullable',
            'option_3' => 'nullable',
            'correct_answer' => 'nullable',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error: Some fields are empty');
        }

        if (!$request->has('id') || $request->id === null) {
            return redirect()->back()->with('error', 'Test ID is required.');
        }
    
        $question = new Question();
        $question->question_title = $request->input('question_title');
        $question->test_id = $request->id;
        $question->option1 = $request->input('option_1');
        $question->option2 = $request->input('option_2');
        $question->option3 = $request->input('option_3');
        $question->option4 = $request->input('option_4');
        $question->answer = $request->input('correct_answer');
        $question->save();
   

        Alert::success('Success', 'Question added successfully');
        return redirect()->route('viewquestions');
    }



    public function editQuestion(Request $request)
    {
        $id = $request->id;
        $editquestiondetails = Question::find($id);
        return view('editquestion', compact('editquestiondetails'));
       
    }

    
    public function deletequestion($id)
    {
    
        $question = Question::find($id);
        if (!$question) {
            return redirect()->back()->with('error', 'User not found');
        }
        Question::where('id', $id)->update(['is_delete' => 0]);
        echo("Deleted");
        // return redirect()->route('editQuestion')->with('success', 'User updated successfully.');
    }
    public function testing(){
echo("Working");
    }

}
