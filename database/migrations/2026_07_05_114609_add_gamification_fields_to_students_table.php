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
        // حقل لتخزين عدد الأيام المتتالية، وقيمته الافتراضية 0 عند التسجيل
        $table->integer('daily_streak')->default(0);

        // حقل لتخزين تاريخ آخر نشاط، ويسمح أن يكون فارغاً (null) في البداية
        $table->date('last_activity_date')->nullable();
    });
}

public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        // في حال أردنا التراجع عن هذا التهجير، نقوم بحذف الحقلين
        $table->dropColumn(['daily_streak', 'last_activity_date']);
    });
}
};
