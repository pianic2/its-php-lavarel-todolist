<?php

namespace Tests\Feature;

use App\Models\TaskList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoReadOnlyTest extends TestCase
{
    use RefreshDatabase;

    public function test_read_only_demo_allows_navigation(): void
    {
        config(['app.demo_read_only' => true]);

        $this->get(route('lists.index'))->assertOk();
    }

    public function test_read_only_demo_blocks_writes(): void
    {
        config(['app.demo_read_only' => true]);

        $this->post(route('lists.store'), [
            'name' => 'Injected list',
            'description' => 'This write should be blocked in the public demo.',
        ])->assertForbidden();

        $this->assertDatabaseMissing('lists', [
            'name' => 'Injected list',
        ]);
    }

    public function test_read_only_demo_blocks_deletes(): void
    {
        config(['app.demo_read_only' => true]);

        $list = TaskList::factory()->create();

        $this->delete(route('lists.destroy', $list))->assertForbidden();

        $this->assertDatabaseHas('lists', [
            'id' => $list->id,
        ]);
    }
}
