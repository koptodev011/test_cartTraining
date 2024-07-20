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
use App\Models\Video;
use Carbon\Carbon;


class VideoUploadController extends Controller
{
    public function videoupload(Request $request)
    {
        // $videos = Video::all();
        $videos = Video::whereIn('role', [1])->get();
        return view('videoUpload', compact('videos'));
    }



    public function uploadVideo(Request $request)
    {

        $request->validate([
            'video' => 'required|mimes:mp4,ogx,oga,ogg,webm',
            'title' => 'required',
            'day' => 'required',
            'role' => 'required'
        ]);
        
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $file_name = $file->getClientOriginalName();
            $file->move(public_path('upload'), $file_name);
            
            $title=$request->input('title');
            $day=$request->input('day');
            $role=$request->input('role');
            $video = new Video();
            $video->video = $file_name;
            $video->title = $title;
            $video->day = $day;
            $video->role = $role;     
            $video->save();
            return view('videoUpload', compact('videos'));
        }
    }


   
    public function editvideodetails(Request $request)
{
    $id = $request->id;
    $videodetails = Video::find($id);
    return view('editvideoupload', compact('videodetails'));
}


public function updatevideodetails(Request $request, $id)
{
   
    $id = $request->id;
 
    // $video = Video::findOrFail($id);
    $videodetails = Video::find($id);
  
    $validator = Validator::make($request->all(), [
        'plan-name' => 'required|string|max:255',
        'plan-fees' => 'required',
        'plan-duration' => 'nullable|string',
        'plan-description' => 'required',
    ]);

  
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $name = $request->input('plan-name');
    $fees = $request->input('plan-fees');
    $duration = $request->input('plan-duration');
    $description= $request->input('plan-description');

    $plan->plane_name = $name;
    $plan->plane_fees = $fees;
    $plan->plane_duration = $duration;
    $plan->plane_description=$description;

    $plan->save();
    return redirect()->route('planeManagement')->with('success', 'User updated successfully.');
}



public function getStudentVideo(Request $request)
{
    $studentVideos = Video::where('role', 1)->get();

    if ($studentVideos->isEmpty()) {
        return response()->json(['message' => 'Student videos not found'], 404);
    }

    $videos = [];
    foreach ($studentVideos as $studentVideo) {
        $videoPath = url('upload/' . $studentVideo->video);

        $videos[] = [
            
            'video' => $studentVideo,
            'video_path' => $videoPath,
        ];
    }

    return response()->json($videos);
}



public function getTrainervideo(Request $request)
{
    $studentVideos = Video::where('role', 2)->get();

    if ($studentVideos->isEmpty()) {
        return response()->json(['message' => 'Student videos not found'], 404);
    }

    $videos = [];
    foreach ($studentVideos as $studentVideo) {
        $videoPath = url('upload/' . $studentVideo->video);

        $videos[] = [
            
            'video' => $studentVideo, // Include the entire $studentVideo object
            'video_path' => $videoPath,
        ];
    }

    return response()->json($videos);
}





    
}
