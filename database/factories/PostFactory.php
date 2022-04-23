<?php


namespace Database\Factories;


use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{

    public function definition()
    {
        return [
            'title' => $this->faker->realText(40),
            'description' => $this->faker->paragraph,
            'image' => $this->faker->image('public/storage/images', 640, 480, null, false),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
