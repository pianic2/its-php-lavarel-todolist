<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskListOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_create_page_is_served_under_list(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = TaskList::factory()->create();
        $list->users()->attach($user->id);

        $this->get(route('lists.tasks.create', $list))
            ->assertOk()
            ->assertSee($list->name);
    }

    public function test_task_is_created_inside_list_route(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = TaskList::factory()->create();
        $list->users()->attach($user->id);

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

    public function test_task_store_requires_title(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = TaskList::factory()->create();
        $list->users()->attach($user->id);

        $this->from(route('lists.tasks.create', $list))
            ->post(route('lists.tasks.store', $list), [
                'title' => '',
                'description' => 'Senza titolo non passa.',
                'is_completed' => '0',
            ])
            ->assertRedirect(route('lists.tasks.create', $list))
            ->assertSessionHasErrors('title');

        $this->assertDatabaseMissing('tasks', [
            'description' => 'Senza titolo non passa.',
            'list_id' => $list->id,
        ]);
    }

    public function test_task_update_requires_title(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = TaskList::factory()->create();
        $list->users()->attach($user->id);

        $task = Task::factory()->forList($list->id)->create([
            'title' => 'Titolo originale',
        ]);

        $this->from(route('lists.tasks.edit', [$list, $task]))
            ->put(route('lists.tasks.update', [$list, $task]), [
                'title' => '',
                'description' => 'Tentativo non valido.',
                'is_completed' => '1',
            ])
            ->assertRedirect(route('lists.tasks.edit', [$list, $task]))
            ->assertSessionHasErrors('title');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Titolo originale',
        ]);
    }

    public function test_task_cannot_be_served_from_another_list_route(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $firstList = TaskList::factory()->create();
        $secondList = TaskList::factory()->create();
        $firstList->users()->attach($user->id);

        $task = Task::factory()->forList($firstList->id)->create();

        $this->get(route('lists.tasks.show', [$secondList, $task]))
            ->assertNotFound();
    }

    public function test_deleting_list_deletes_its_tasks(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = TaskList::factory()->create();
        $list->users()->attach($user->id);

        $task = Task::factory()->forList($list->id)->create();

        $this->delete(route('lists.destroy', $list))->assertRedirect(route('lists.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_list_show_filters_open_tasks(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = TaskList::factory()->create();
        $list->users()->attach($user->id);
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
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = TaskList::factory()->create();
        $list->users()->attach($user->id);
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
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = TaskList::factory()->create();
        $list->users()->attach($user->id);
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
