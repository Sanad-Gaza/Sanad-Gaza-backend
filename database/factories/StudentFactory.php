<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

//     {
//     "identity_number": "98745343345",
//     "first_name": "ابراهيم",
//     "father_name": "أحمد",
//     "grandfather_name": "محمود",
//     "family_name": "خليل",
//     "username": "هibra_studen",
//     "email": "sara@ex5mple.com",
//     "password": "123456789",
//     "phone_number": "0596688777",
//     "status": "active",
//     "grade_id": 1,
//     "section": "أ",
//     "health_status": "سليم، لا يعاني من أمراض مزمنة",
//     "gender": "female",
//     "birth_date": "2015-05-20"
// }
    public function definition(): array
    {

        return [
            'grade_id' => fake()->numberBetween(1, 9), // افتراضياً، نختار الصفوف من 1 إلى 9
            'section' => fake()->randomElement(['أ', 'ب', 'ج', 'د']), // افتراضياً، نختار القسم من 4 أقسام
            'health_status' => fake()->randomElement(['سليم، لا يعاني من أمراض مزمنة', 'يعاني من حساسية بسيطة', 'يعاني من مرض مزمن']), // حالة صحية عشوائية
            'gender' => fake()->randomElement(['male', 'female']), // الجنس عشوائي
            'birth_date' => fake()->dateTimeBetween('-10 years', '-2 years')->format('Y-m-d'), // تاريخ الميلاد عشوائي

            'points_balance' => fake()->numberBetween(50, 1000), // نقاط عشوائية لاختبار ترتيب المتصدرين
            'daily_streak' => fake()->numberBetween(0, 15), // شعلة عشوائية بين 0 و 15 يوم متتالي
            'last_activity_date' => fake()->dateTimeBetween('-3 days', 'now')->format('Y-m-d'), // تاريخ نشاط خلال آخر 3 أيام لاختبار انقطاع الشعلة
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
