@extends('layouts.app')

@section('content')
    <div class="container container--sm">
        <div class="u-mb-4">
            <h2>Modifica nota</h2>
            <p class="u-text-muted">Aggiorna i dati della nota selezionata.</p>
        </div>

        @include('tasks._form', [
            'task' => $task,
            'action' => route('tasks.update', $task),
            'method' => 'PUT',
            'submitLabel' => 'Aggiorna nota',
        ])
    </div>
@endsection
