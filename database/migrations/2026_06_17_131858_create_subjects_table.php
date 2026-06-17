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
    Schema::create('subjects', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grade_id')
            ->constrained()
            ->restrictOnDelete();
        $table->string('name');
        $table->string('description')->nullable();
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
        $table->unique(['grade_id', 'name']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
