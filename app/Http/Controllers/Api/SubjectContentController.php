<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SubjectContentService;
use Illuminate\Http\Request;

class SubjectContentController extends Controller
{
    protected $contentService;

    public function __construct(SubjectContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function getContent(Request $request, $subject_id)
    {
        $data = $this->contentService->getContent($request->user(), $subject_id);

        return response()->json($data, 200);
    }

    public function completeTask(Request $request, $task_id)
    {
        $result = $this->contentService->completeTask($request->user(), $task_id);

        return response()->json($result, 200);
    }

    public function getSubjectMap(Request $request, $subject_id)
    {
        $data = $this->contentService->getSubjectMap($request->user(), $subject_id);

        return response()->json($data, 200);
    }
}
