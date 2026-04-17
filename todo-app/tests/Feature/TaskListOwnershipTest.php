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

    public function test_list_show_filters_open_tasks(): void
    {
        $list = TaskList::factory()->create();
        Task::factory()->forList($list->id)->create([
            'title' => 'Nota ancora aperta',
            'is_completed' => false,
        ]);
        Task::factory()->forList($list->id)->create([
            'title' => 'Nota gia completata',
            'is_completed' => true,
        ]);

        $this->get(route('lists.show', ['list' => $list, 'filter' => 'open']))
            ->assertOk()
            ->assertSee('Nota ancora aperta')
            ->assertDontSee('Nota gia completata');
    }

    public function test_list_show_filters_done_tasks(): void
    {
        $list = TaskList::factory()->create();
        Task::factory()->forList($list->id)->create([
            'title' => 'Nota ancora aperta',
            'is_completed' => false,
        ]);
        Task::factory()->forList($list->id)->create([
            'title' => 'Nota gia completata',
            'is_completed' => true,
        ]);

        $this->get(route('lists.show', ['list' => $list, 'filter' => 'done']))
            ->assertOk()
            ->assertSee('Nota gia completata')
            ->assertDontSee('Nota ancora aperta');
    }

    public function test_invalid_list_filter_falls_back_to_all_tasks(): void
    {
        $list = TaskList::factory()->create();
        Task::factory()->forList($list->id)->create([
            'title' => 'Nota ancora aperta',
            'is_completed' => false,
        ]);
        Task::factory()->forList($list->id)->create([
            'title' => 'Nota gia completata',
            'is_completed' => true,
        ]);

        $this->get(route('lists.show', ['list' => $list, 'filter' => 'banana']))
            ->assertOk()
            ->assertSee('Nota ancora aperta')
            ->assertSee('Nota gia completata');
    }
}
