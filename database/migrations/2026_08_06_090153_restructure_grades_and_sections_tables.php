<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. إنشاء جدول الشعب (sections)
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades')->cascadeOnDelete();
            $table->string('name'); // مثال: "أ", "ب", "A"
            $table->timestamps();

            // لا يمكن تكرار نفس اسم الشعبة داخل نفس الصف
            $table->unique(['grade_id', 'name']);
        });

        // 2. تعديل جدول الطلاب (students)
        Schema::table('students', function (Blueprint $table) {
            // حذف حقل الشعبة النصي القديم
            $table->dropColumn('section');
            // إضافة علاقة الشعبة الجديدة
            $table->foreignId('section_id')->nullable()->after('grade_id')->constrained('sections')->nullOnDelete();
        });

        // 3. استبدال جدول الربط القديم بجدول الربط الصحيح بين المعلم والشعبة
        Schema::dropIfExists('grade_teacher');

        Schema::create('section_teacher', function (Blueprint $table) {
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->primary(['section_id', 'teacher_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // عمليات التراجع في حال احتجت لها
        Schema::dropIfExists('section_teacher');

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
            $table->string('section')->nullable();
        });

        Schema::dropIfExists('sections');
    }
};
