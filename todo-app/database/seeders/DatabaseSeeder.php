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
        // Create sample lists and tasks for local development
        $personal = TaskList::create([
            'name' => 'Personal',
            'description' => 'Personal tasks',
        ]);

        $work = TaskList::create([
            'name' => 'Work',
            'description' => 'Work related tasks',
        ]);

        $shopping = TaskList::create([
            'name' => 'Shopping',
            'description' => 'Shopping related tasks',
        ]);


        $project = TaskList::create([
            'name' => 'Project',
            'description' => 'Project related tasks',
        ]);

        Task::factory()->count(3)->forList($personal->id)->create();
        Task::factory()->count(4)->forList($work->id)->create();
        Task::factory()->count(8)->forList($shopping->id)->create();
        Task::factory()->count(5)->forList($project->id)->create();
    }
}
