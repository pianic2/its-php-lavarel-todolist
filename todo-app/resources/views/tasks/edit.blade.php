@extends('layouts.app')

@section('content')
    <div class="container container--sm">
        <div class="u-mb-4">
            <h2>Modifica nota</h2>
            <p class="u-text-muted">Lista: {{ $list->name }}</p>
        </div>

        @include('tasks._form', [
            'task' => $task,
            'list' => $list,
            'action' => route('lists.tasks.update', [$list, $task]),
            'method' => 'PUT',
            'submitLabel' => 'Aggiorna nota',
        ])
    </div>
@endsection
