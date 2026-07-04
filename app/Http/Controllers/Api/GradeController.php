<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Services\GradeService;
use App\Http\Resources\GradeResource;
use App\Http\Resources\SubjectResource;

class GradeController extends Controller
{
    public function store(StoreGradeRequest $request, GradeService $gradeService)
    {
        $grade = $gradeService->createGrade($request->validated());

        return response()->json([
            'message' => 'تم إنشاء الصف بنجاح',
            'data'    => new GradeResource($grade),
        ], 201);
    }


    public function index(GradeService $gradeService)
    {
        $grades = $gradeService->getAllGrades();

        return GradeResource::collection($grades);
    }

    public function show($id, GradeService $gradeService)
    {
        $grade = $gradeService->getGradeById($id);

        return new GradeResource($grade);
    }

    public function update(UpdateGradeRequest $request, $id, GradeService $gradeService)
    {
        $grade = $gradeService->updateGrade($id, $request->validated());

        return response()->json([
            'message' => 'تم تحديث الصف بنجاح',
            'data'    => new GradeResource($grade),
        ], 200);
    }

    public function destroy($id, GradeService $gradeService)
    {
        $gradeService->deleteGrade($id);

        return response()->json([
            'message' => 'تم حذف الصف بنجاح',
        ], 200);
    }

    // Get subjects by grade id
    public function getSubjectsByGradeId($id, GradeService $gradeService)
    {
        $subjects = $gradeService->getSubjectsByGradeId($id);

        return SubjectResource::collection($subjects);
    }
}
