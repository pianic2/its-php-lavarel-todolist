@extends('layouts.app')

@section('content')
    <section class="notes-page">
        <div class="notes-page__header">
            <div>
                <p class="app-eyebrow">Lista selezionata</p>
                <h2>{{ $list->name }}</h2>
                <p class="u-text-muted">{{ $list->description ?: 'Nessuna descrizione disponibile. Fallback attivo.' }}</p>
            </div>

            <div class="notes-page__actions u-inline-flex u-gap-2">
                <a href="{{ route('lists.edit', $list) }}" class="button button--outline">Modifica lista</a>
                <a href="{{ route('tasks.create') }}" class="button button--primary">Nuova nota</a>
            </div>
        </div>

        @include('tasks._notes-grid', [
            'tasks' => $tasks,
            'emptyTitle' => 'Questa lista è vuota',
            'emptyDescription' => 'Crea una nota e, se necessario, assegnala a questa lista dal modulo della nota.',
        ])
    </section>
@endsection
