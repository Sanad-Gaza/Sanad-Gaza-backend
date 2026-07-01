<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Services\StudentService;
use App\Http\Resources\StudentResource;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function store(StoreStudentRequest $request)
    {
        $student = $this->studentService->createStudent($request->validated());

        return response()->json([
            'message' => 'تم إنشاء حساب الطالب بنجاح',
            'data'    => new StudentResource($student),
        ], 201);
    }

    public function index()
    {
        $students = $this->studentService->getAllStudents();
        return StudentResource::collection($students);
    }
}
