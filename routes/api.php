<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\TeacherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentDashboardController;
use App\Http\Controllers\Api\SubjectContentController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Grade routes
    Route::post('/create-grade', [GradeController::class, 'store']);
    Route::get('/grades', [GradeController::class, 'index']);
    Route::put('/grades/{id}', [GradeController::class, 'update']);
    Route::delete('/grades/{id}', [GradeController::class, 'destroy']);
    Route::get('/grades/{id}', [GradeController::class, 'show']);
    Route::get('/grades/{id}/subjects', [GradeController::class, 'getSubjectsByGradeId']);


    //Student routes
    Route::post('/create-student', [StudentController::class, 'store']);
    Route::get('/students', [StudentController::class, 'index']);
    Route::get('/students/{id}', [StudentController::class, 'show']);
    Route::put('/students/{id}', [StudentController::class, 'update']);
    Route::delete('/students/{id}', [StudentController::class, 'destroy']);
    //Student dashboard route
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index']);


    //مسار لوحة تحكم الطالب
    //Subject routes
    Route::post('/create-subject', [SubjectController::class, 'store']);
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::put('/subjects/{id}', [SubjectController::class, 'update']);
    Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);
    Route::get('/subjects/{id}', [SubjectController::class, 'show']);

    //Teacher routes
    Route::post('/create-teacher', [TeacherController::class, 'store']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::get('/teachers/{id}', [TeacherController::class, 'show']);
    Route::put('/teachers/{id}', [TeacherController::class, 'update']);
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);


    // مسار جلب محتوى المادة (الوحدات والمهام)
    Route::get('/subjects/{subject_id}/content', [SubjectContentController::class, 'getContent']);

    // مسار إنجاز المهمة وإضافة النقاط (سنقوم ببرمجة الدالة الخاصة به الآن)
    Route::post('/tasks/{task_id}/complete', [SubjectContentController::class, 'completeTask']);
});
