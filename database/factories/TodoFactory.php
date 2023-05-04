<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as FakerGenerator; 

 

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
        ];
    }
 
 
}