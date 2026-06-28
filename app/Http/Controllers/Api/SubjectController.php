<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Services\SubjectService;
use App\Http\Resources\SubjectResource;

class SubjectController extends Controller
{
    public function store(StoreSubjectRequest $request, SubjectService $subjectService)
    {
        $subject = $subjectService->createSubject($request->validated());

        return response()->json([
            'message' => 'تم إنشاء المادة بنجاح',
            'data'    => new SubjectResource($subject),
        ], 201);
    }


    public function index(SubjectService $subjectService)
    {
        $subjects = $subjectService->getAllSubjects();
        return SubjectResource::collection($subjects);
    }


    public function show($id, SubjectService $subjectService)
    {
        $subject = $subjectService->getSubjectById($id);

        return new SubjectResource($subject);
    }



    public function update(UpdateSubjectRequest $request, $id, SubjectService $subjectService)
    {
        $subject = $subjectService->updateSubject($id, $request->validated());

        return response()->json([
            'message' => 'تم تحديث المادة بنجاح',
            'data'    => new SubjectResource($subject),
        ], 200);
    }


    public function destroy($id, SubjectService $subjectService)
    {
        $subjectService->deleteSubject($id);

        return response()->json([
            'message' => 'تم حذف المادة بنجاح',
        ], 200);
    }
}
