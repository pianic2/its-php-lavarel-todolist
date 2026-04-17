<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use App\Models\TaskList;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'is_completed' => $this->faker->boolean(20),
            'list_id' => TaskList::factory(),
        ];
    }

    /**
     * Attach task to a specific list id.
     */
    public function forList(int $listId)
    {
        return $this->state(function (array $attributes) use ($listId) {
            return ['list_id' => $listId];
        });
    }
}
