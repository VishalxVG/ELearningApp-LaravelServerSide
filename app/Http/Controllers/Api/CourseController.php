<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    //
    public function courseList(){
        $result = Course::select('name','thumbnail','lesson_num','price','id')->get();
        return response()->json([
            'code' => 200,
            'msg' => 'data is available',
            'data' =>$result
        ],200);
    }
}
