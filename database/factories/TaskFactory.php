<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Task::class;

    public function definition()
    {
        return [
            'task_name' => $this->faker->sentence, 
            'task_id' => function () {
                return \App\Models\Task::factory()->create()->id;
            },
        ];
    }
}
