<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TaskList;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample lists and tasks for local development
        $personal = TaskList::create([
            'name' => 'Personal',
            'description' => 'Personal tasks',
        ]);

        $work = TaskList::create([
            'name' => 'Work',
            'description' => 'Work related tasks',
        ]);

        Task::factory()->count(3)->forList($personal->id)->create();
        Task::factory()->count(4)->forList($work->id)->create();
    }
}
