<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
class PDFController extends Controller
{



   public function export(Request $request)
   {
       $validatedData = $request->validate([
           'role' => 'required',
           'format' => 'required',
       ]);

       $role = $request->input('role');
       $format = $request->input('format');


 $users = User::where('role', $role)->get();


 $data=[
    'title' => 'Shiv Suman Moters',
    'date' => date('m/d/Y'),
    'users' => $users
];
$pdf=Pdf::loadView('products.generate-user-pdf',$data);
return $pdf->download('Employees.pdf');
   }

   
}



