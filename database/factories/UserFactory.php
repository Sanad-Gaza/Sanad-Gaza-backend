<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
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
    public function definition(): array
    {

        return [
            'identity_number' => fake()->unique()->numerify('###########'), // رقم الهوية المكون من 11 رقمًا
            'first_name' => fake()->firstName(),
            'father_name' => fake()->firstName(),
            'grandfather_name' => fake()->firstName(),
            'family_name' => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => fake()->unique()->numerify('05########'), // رقم الهاتف
            'role' => fake()->randomElement(['student', 'teacher', 'admin']),
            'status' => 'active', // الحالة الافتراضية للمستخدم
            'profile_picture' => null, // صورة الملف الشخصي الافتراضية
            'email_verified_at' => now(), // التحقق من البريد الإلكتروني بشكل افتراضي
            'password' => static::$password ??= Hash::make('password'), // كلمة المرور الافتراضية
            'remember_token' => Str::random(10), // رمز التذكر العشوائي
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
