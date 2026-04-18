<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lists = [
            [
                'name' => 'Portfolio launch',
                'description' => 'Tasks used to prepare the project for public sharing.',
                'tasks' => [
                    ['title' => 'Write project README', 'description' => 'Document stack, setup, routes, tests and future improvements.', 'is_completed' => true],
                    ['title' => 'Add GitHub Actions pipeline', 'description' => 'Run Laravel tests and Vite production build on every push.', 'is_completed' => true],
                    ['title' => 'Publish repository link on LinkedIn', 'description' => 'Add a short technical summary and GitHub URL.', 'is_completed' => false],
                ],
            ],
            [
                'name' => 'Client work',
                'description' => 'Example operational list for a small freelance workflow.',
                'tasks' => [
                    ['title' => 'Collect feature requirements', 'description' => 'Clarify CRUD flows, validation rules and delivery scope.', 'is_completed' => true],
                    ['title' => 'Prepare first demo', 'description' => 'Show the Blade interface with seeded data.', 'is_completed' => false],
                    ['title' => 'Review feedback', 'description' => 'Turn client notes into implementation tasks.', 'is_completed' => false],
                ],
            ],
            [
                'name' => 'Learning roadmap',
                'description' => 'Next steps for improving the Laravel project.',
                'tasks' => [
                    ['title' => 'Add authentication', 'description' => 'Protect lists by user account.', 'is_completed' => false],
                    ['title' => 'Expose JSON API endpoints', 'description' => 'Prepare the backend for a future frontend client.', 'is_completed' => false],
                    ['title' => 'Deploy a live demo', 'description' => 'Choose a hosting provider and configure production environment variables.', 'is_completed' => false],
                ],
            ],
        ];

        foreach ($lists as $listData) {
            $tasks = $listData['tasks'];
            unset($listData['tasks']);

            $list = TaskList::create($listData);

            foreach ($tasks as $taskData) {
                Task::create([
                    ...$taskData,
                    'list_id' => $list->id,
                ]);
            }
        }
    }
}
