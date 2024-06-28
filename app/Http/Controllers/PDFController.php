<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
class PDFController extends Controller
{
   public function generatePDF(){
//     $users =User::get();
//     $data = [
//         'title' => 'Welcome to the code',
//         'date' => date(m/d/Y),
//         'users' => $users
//     ];

//     $pdf = PDF::loadView('myPDF',$data);
// dd();
//     return $pdf->download('Student.pdf');
   }
  

   public function index(){
    $users =User::get();
$data=[
    'title' => 'Funda of Web It',
    'date' => date('m/d/Y'),
    'users' => $users
];


$pdf=Pdf::loadView('products.generate-user-pdf',$data);
return $pdf->download('invoice.pdf');
   }


   public function export(Request $request)
   {
       // Validate the incoming request data
       $validatedData = $request->validate([
           'role' => 'required',
           'format' => 'required',
       ]);

       $role = $request->input('role');
       $format = $request->input('format');

 $users = User::where('role', $role)->get();

 $data=[
    'title' => 'Funda of Web It',
    'date' => date('m/d/Y'),
    'users' => $users
];
$pdf=Pdf::loadView('products.generate-user-pdf',$data);
return $pdf->download('invoice.pdf');
   }

   
}



