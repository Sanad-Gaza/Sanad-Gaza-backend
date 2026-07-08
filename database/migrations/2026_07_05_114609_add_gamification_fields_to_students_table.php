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
    Schema::table('students', function (Blueprint $table) {
        $table->integer('daily_streak')->default(0);

        $table->date('last_activity_date')->nullable();
    });
}

public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn(['daily_streak', 'last_activity_date']);
    });
}
};
