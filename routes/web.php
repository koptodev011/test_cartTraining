<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return view('auth.login');
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


//Authentication routes
Route::post('/Authlogin', [App\Http\Controllers\AuthController::class,'Authlogin'])->name('Authlogin');










//Employee Routes
Route::get('/employee',[App\Http\Controllers\EmployeeController::class,'employee'])->name('employee'); 
Route::post('/create', [App\Http\Controllers\EmployeeController::class,'createEmployee'])->name('employee.create');
Route::get('/edit-user/{user}',[App\Http\Controllers\EmployeeController::class,'edit'])->name('edit.user')->middleware('auth');
Route::get('/editemployee',[App\Http\Controllers\EmployeeController::class,'editemployee'])->name('editemployee')->middleware('auth'); 
Route::post('/updateemployee-form/{id}', [App\Http\Controllers\EmployeeController::class, 'updateemployee'])->name('updateemployee')->middleware('auth');
Route::get('/delete/{id}', [App\Http\Controllers\EmployeeController::class, 'delete'])->name('delete')->middleware('auth');
Route::get('/deleated-employeelist/{id}', [App\Http\Controllers\EmployeeController::class, 'deletedemployeelist'])->name('deletedemployeelist')->middleware('auth');


//Students Routes
Route::get('/student',[App\Http\Controllers\StudentController::class,'student'])->name('student')->middleware('auth');
Route::post('/submit-form',[App\Http\Controllers\StudentController::class,'submitForm'])->middleware('auth');
Route::get('/editstudent',[App\Http\Controllers\StudentController::class,'editstudent'])->name('editstudent')->middleware('auth'); 
Route::post('/updatestudent-form/{id}', [App\Http\Controllers\StudentController::class, 'updatestudent'])->name('updatestudent')->middleware('auth');
Route::get('/deletestudent/{id}', [App\Http\Controllers\StudentController::class, 'deletestudent'])->name('deletestudent')->middleware('auth');
Route::get('/deleated-studentslist/{id}', [App\Http\Controllers\StudentController::class, 'deletedstudentlist'])->name('deletedstudentlist')->middleware('auth');


//PDF Route
// Route::get('/generate-pdf',[App\Http\Controllers\PDFController::class,'index'])->middleware('auth');
Route::post('/export',[App\Http\Controllers\PDFController::class,'export'])->name('export')->middleware('auth');


//Car Management Routes
Route::get('/car-management',[App\Http\Controllers\CarManagementController::class,'carManagement'])->name('carManagement')->middleware('auth');
Route::post('/car-details', [App\Http\Controllers\CarManagementController::class,'addCarDetails'])->middleware('auth');
Route::get('/edit-cardetails',[App\Http\Controllers\CarManagementController::class,'editCarDetails'])->name('edit-cardetails')->middleware('auth'); 
Route::post('/updatecardetails/{id}', [App\Http\Controllers\CarManagementController::class, 'updatecardetails'])->name('updatecardetails')->middleware('auth');
Route::get('/deletecar/{id}', [App\Http\Controllers\CarManagementController::class, 'deletecar'])->name('deletecar')->middleware('auth');






//Plane Management Route
Route::get('/plane-management',[App\Http\Controllers\PlaneManagementController::class,'planeManagement'])->name('planeManagement')->middleware('auth');
Route::post('/addPlane-details', [App\Http\Controllers\PlaneManagementController::class,'addPlaneDetails'])->middleware('auth');
// Route::post('/updateUserActiveStatus', [UserController::class, 'updateUserActiveStatus'])->name('updateUserActiveStatus');
Route::get('/edit-plandetails',[App\Http\Controllers\PlaneManagementController::class,'editplanDetails'])->name('edit-plandetails')->middleware('auth'); 
Route::post('/updateplandetails/{id}', [App\Http\Controllers\PlaneManagementController::class, 'updateplandetails'])->name('updateplandetails')->middleware('auth');
Route::get('/deactivate-plandetails/{id}', [App\Http\Controllers\PlaneManagementController::class, 'deactivateplan'])->name('deactivateplan')->middleware('auth');
Route::post('/submit-form891',[App\Http\Controllers\PlaneManagementController::class,'submitForm123'])->middleware('auth');



//Sclary management routes
Route::get('/sclary-management',[App\Http\Controllers\SclaryManagementController::class,'sclaryManagement'])->name('sclaryManagement')->middleware('auth');
Route::post('/searchRecords',[App\Http\Controllers\SclaryManagementController::class,'searchRecords'])->middleware('auth');


//Video upload routes
Route::get('/video-upload',[App\Http\Controllers\VideoUploadController::class,'videoupload'])->name('videoupload')->middleware('auth');
Route::post('/uploadVideo',[App\Http\Controllers\VideoUploadController::class,'uploadVideo'])->middleware('auth');
// Route::get('/fetchvideos', [App\Http\Controllers\VideoUploadController::class,'fetchVideos']);
Route::get('/editvideodetails',[App\Http\Controllers\VideoUploadController::class,'editvideodetails'])->name('editvideodetails')->middleware('auth');
Route::post('/updatevideodetails/{id}', [App\Http\Controllers\VideoUploadController::class, 'updatevideodetails'])->name('updatevideodetails')->middleware('auth');



//Branch routes
Route::get('/branch',[App\Http\Controllers\BranchController::class,'branch'])->name('branch')->middleware('auth');
Route::post('/createbranch', [App\Http\Controllers\BranchController::class,'createbranch'])->name('createbranch')->middleware('auth');



//Dashboard routes
Route::get('/home1', [App\Http\Controllers\PaymentController::class, 'showGraph']);
Route::get('/branchAnalysis', [App\Http\Controllers\PaymentController::class, 'branchAnalysis']);
Route::get('/branch',[App\Http\Controllers\BranchController::class,'branch'])->name('branch');
Route::get('/dashbord', [App\Http\Controllers\PaymentController::class, 'dashborddata'])->name('dashbord')->middleware('auth');
Route::get('/planAnalysis', [App\Http\Controllers\PaymentController::class, 'planAnalysis']);
Route::get('/monthlyAnalysis', [App\Http\Controllers\PaymentController::class, 'monthlyAnalysis']);



//Expense routes
Route::get('/expensedata', [App\Http\Controllers\ExpenseController::class, 'Expensedata'])->name('expense');
// Route::get('/viewexpense', [App\Http\Controllers\ExpenseController::class, 'viewExpense'])->name('viewexpense');
Route::get('/viewexpense',[App\Http\Controllers\ExpenseController::class,'viewExpense'])->name('viewexpense');
Route::get('viewexpense/{trainer_id}', [App\Http\Controllers\ExpenseController::class, 'viewExpense'])->name('viewexpense');



//Batch Routes


//Test management routes
Route::get('/test', [App\Http\Controllers\TestController::class, 'test'])->name('test');
Route::post('/addTest', [App\Http\Controllers\TestController::class,'addTest'])->name('/addTest')->middleware('auth');
Route::get('/editTest', [App\Http\Controllers\TestController::class, 'editTest'])->name('editTest');
Route::get('/viewquestions', [App\Http\Controllers\TestController::class, 'viewquestions'])->name('viewquestions');
Route::post('/addQuestion', [App\Http\Controllers\TestController::class,'addQuestion'])->name('addQuestion')->middleware('auth');
Route::get('/editQuestion', [App\Http\Controllers\TestController::class, 'editQuestion'])->name('editQuestion');
Route::get('/deleteQuestion', [App\Http\Controllers\TestController::class, 'deleteQuestion'])->name('deletequestion');

//Allocate Students Route
Route::get('/PresentTeachers', [App\Http\Controllers\StudentAllocationController::class, 'PresentTeachers'])->name('PresentTeachers');

