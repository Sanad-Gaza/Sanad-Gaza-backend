<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_unit', function (Blueprint $table) {
            $table->id();

            // ربط الطالب بالوحدة (المستوى)
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');

            // حالة المستوى (مقفل، متاح، أو منجز)
            $table->enum('status', ['locked', 'unlocked', 'completed'])->default('locked');

            // عدد النجوم (من 0 إلى 3)
            $table->tinyInteger('stars')->default(0);

            $table->timestamps();

            // منع تكرار نفس الوحدة لنفس الطالب
            $table->unique(['student_id', 'unit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_unit');
    }
};
