<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
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

    public function show($id)
    {
        $student = $this->studentService->getStudentById($id);
        return new StudentResource($student);
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $student = $this->studentService->updateStudent($id, $request->validated());

        return response()->json([
            'message' => 'تم تحديث بيانات الطالب بنجاح',
            'data'    => new StudentResource($student),
        ], 200);
    }

    public function destroy($id)
    {
        $this->studentService->deleteStudent($id);

        return response()->json([
            'message' => 'تم حذف حساب الطالب بنجاح',
        ], 200);
    }
}
