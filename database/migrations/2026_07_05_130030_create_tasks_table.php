<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
           $table->id();

        // ربط المهمة بالوحدة التابعة لها
        $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();

        $table->string('title'); // مثلاً: "درس الترتيب التصاعدي"
        $table->text('description')->nullable();

        // إضافة نوع المهمة (فيديو، ملف، أو اختبار)
        $table->enum('type', ['video', 'document', 'quiz']);

        // مسار أو رابط المحتوى
        $table->string('url')->nullable();

        $table->integer('points');
        $table->dateTime('due_date')->nullable(); // جعلناه اختيارياً لأن بعض المهام مجرد فيديوهات للمشاهدة

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
