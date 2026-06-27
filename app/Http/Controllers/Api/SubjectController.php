<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubjectRequest;
use App\Services\SubjectService;
use App\Http\Resources\SubjectResource;

class SubjectController extends Controller
{
    public function store(StoreSubjectRequest $request, SubjectService $subjectService)
    {
        $subject = $subjectService->createSubject($request->validated());

        return response()->json([
            'message' => 'تم إنشاء المادة بنجاح',
            'data'    => $subject, 
        ], 201);
    }
}
