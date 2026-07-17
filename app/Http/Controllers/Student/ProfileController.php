<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;
        $student->load('academicClass');

        return view('student.profile', compact('student'));
    }
}
