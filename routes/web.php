<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/studentform', function () {
    return view('studentform');
})->name('studentform');

Route::get('/form',function(){
    return view('form');
})->name('form');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/studentPage',[App\Http\Controllers\HomeController::class,'studentpage'])->name('studentpage'); 

// Route::get('/student',[App\Http\Controllers\HomeController::class,'student'])->name('student');
// Route::get('/studentForm',[App\Http\Controllers\HomeController::class,'studentForm'])->name('studentForm');

Route::get('/users', [App\Http\Controllers\HomeController::class, 'getUsers']);
// Route::get('/student',[App\Http\Controllers\HomeController::class,'student']);
Route::get('/studenttest',[App\Http\Controllers\HomeController::class,'studenttest']);
Route::get('/Edit',[App\Http\Controllers\HomeController::class,'edit']);

//StudentEdit 



Route::get('/users/{id}/edit', [App\Http\Controllers\HomeController::class, 'edit'])->name('users.edit');

Route::post('/updatestudent-form/{id}', [App\Http\Controllers\HomeController::class, 'updatestudent'])->name('updatestudent');




//PDF Section

// Route::get('/generate-pdf',[App\Http\Controllers\HomeController::class,'generatePDF']);
Route::get('/studentview', [App\Http\Controllers\HomeController::class, 'studentview'])->name('studentview');




//Employee Routes
Route::get('/employee',[App\Http\Controllers\EmployeeController::class,'employee'])->name('employee'); 
Route::post('/create', [App\Http\Controllers\EmployeeController::class,'createEmployee'])->name('employee.create');
Route::get('/edit-user/{user}',[App\Http\Controllers\EmployeeController::class,'edit'])->name('edit.user');
Route::get('/editemployee',[App\Http\Controllers\EmployeeController::class,'editemployee'])->name('editemployee'); 
Route::post('/updateemployee-form/{id}', [App\Http\Controllers\EmployeeController::class, 'updateemployee'])->name('updateemployee');
Route::get('/delete/{id}', [App\Http\Controllers\EmployeeController::class, 'delete'])->name('delete');


//All students Routes
Route::get('/student',[App\Http\Controllers\HomeController::class,'student'])->name('student');
Route::post('/submit-form',[App\Http\Controllers\StudentController::class,'submitForm']);
Route::get('/editstudent',[App\Http\Controllers\StudentController::class,'editstudent'])->name('editstudent'); 
Route::post('/updatestudent-form/{id}', [App\Http\Controllers\StudentController::class, 'updatestudent'])->name('updatestudent');
Route::get('/deletestudent/{id}', [App\Http\Controllers\StudentController::class, 'deletestudent'])->name('deletestudent');


//PDF Route
Route::get('/generate-pdf',[App\Http\Controllers\PDFController::class,'index']);
Route::post('/export',[App\Http\Controllers\PDFController::class,'export'])->name('export');
