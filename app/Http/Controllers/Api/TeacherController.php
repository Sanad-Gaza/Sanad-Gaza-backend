<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Services\TeacherService;
use App\Http\Resources\TeacherResource;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function store(StoreTeacherRequest $request)
    {
        $teacher = $this->teacherService->createTeacher($request->validated());

        return response()->json([
            'message' => 'تم إنشاء حساب المعلم بنجاح',
            'data'    => new TeacherResource($teacher),
        ], 201);
    }

    public function index()
    {
        $teachers = $this->teacherService->getAllTeachers();
        return TeacherResource::collection($teachers);
    }

    public function show($id)
    {
        $teacher = $this->teacherService->getTeacherById($id);
        return new TeacherResource($teacher);
    }

    public function update(UpdateTeacherRequest $request, $id)
    {
        $teacher = $this->teacherService->updateTeacher($id, $request->all());

        return response()->json([
            'message' => 'تم تحديث بيانات المعلم بنجاح',
            'data'    => new TeacherResource($teacher),
        ], 200);
    }

    public function destroy($id)
    {
        $this->teacherService->deleteTeacher($id);

        return response()->json([
            'message' => 'تم حذف حساب المعلم بنجاح',
        ], 200);
    }
}
