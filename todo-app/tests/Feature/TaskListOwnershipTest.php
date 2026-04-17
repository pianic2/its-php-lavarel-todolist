<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskListOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_create_page_is_served_under_list(): void
    {
        $list = TaskList::factory()->create();

        $this->get(route('lists.tasks.create', $list))
            ->assertOk()
            ->assertSee($list->name);
    }

    public function test_task_is_created_inside_list_route(): void
    {
        $list = TaskList::factory()->create();

        $this->post(route('lists.tasks.store', $list), [
            'title' => 'Nota della lista',
            'description' => 'Deve appartenere alla lista.',
            'is_completed' => '0',
        ])->assertRedirect(route('lists.show', $list));

        $this->assertDatabaseHas('tasks', [
            'title' => 'Nota della lista',
            'list_id' => $list->id,
        ]);
    }

    public function test_task_cannot_be_served_from_another_list_route(): void
    {
        $firstList = TaskList::factory()->create();
        $secondList = TaskList::factory()->create();
        $task = Task::factory()->forList($firstList->id)->create();

        $this->get(route('lists.tasks.show', [$secondList, $task]))
            ->assertNotFound();
    }

    public function test_deleting_list_deletes_its_tasks(): void
    {
        $list = TaskList::factory()->create();
        $task = Task::factory()->forList($list->id)->create();

        $this->delete(route('lists.destroy', $list))->assertRedirect(route('lists.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
