<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
      /**
     * Display the courses page.
     */
    public function index()
    {
        return view('courses.index');
    }
}
