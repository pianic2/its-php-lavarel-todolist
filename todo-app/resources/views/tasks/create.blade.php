@extends('layouts.app')

@section('content')
    <div class="container container--sm">
        <div class="u-mb-4">
            <h2>Nuova nota</h2>
            <p class="u-text-muted">Compila i campi per creare una nuova nota.</p>
        </div>

        @include('tasks._form', [
            'task' => $task,
            'action' => route('tasks.store'),
            'method' => 'POST',
            'submitLabel' => 'Crea nota',
        ])
    </div>
@endsection
