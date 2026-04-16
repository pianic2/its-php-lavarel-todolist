<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use App\Models\TaskList;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /* 
     * Test that the Task model correctly casts
     * the is_completed attribute and has a valid
     * relationship with TaskList.
     * 
     * ---
     * 
     * Controlla che il modello Task casti 
     * correttamente l'attributo is_completed 
     * e abbia una relazione valida con TaskList.
     */
    public function test_task_casts_and_relation()
    {
        $list = TaskList::create(['name' => 'List A']);

        $task = Task::factory()->forList($list->id)->create(['is_completed' => 1]);

        $this->assertIsBool($task->is_completed);
        $this->assertEquals($list->id, $task->list->id);
    }

    /* 
     * Test the completed and pending scopes, as well
     * as the toggleCompleted method.
     * 
     * ---
     * 
     * Testa gli scope completed e pending, così come
     * il metodo toggleCompleted.
     */
    public function test_scopes_and_toggle()
    {
        $list = TaskList::create(['name' => 'List B']);

        Task::factory()->forList($list->id)->create(['is_completed' => false]);
        Task::factory()->forList($list->id)->create(['is_completed' => true]);

        $this->assertCount(1, Task::completed()->get());
        $this->assertCount(1, Task::pending()->get());

        $task = Task::pending()->first();
        $task->toggleCompleted();
        $this->assertTrue($task->fresh()->is_completed);
    }
}
