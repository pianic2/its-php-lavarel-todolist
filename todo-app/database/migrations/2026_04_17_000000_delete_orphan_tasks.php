<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tasks')
            ->whereNull('list_id')
            ->orWhereNotExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('lists')
                    ->whereColumn('lists.id', 'tasks.list_id');
            })
            ->delete();
    }

    public function down(): void
    {
        //
    }
};
