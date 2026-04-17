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
                <a href="{{ route('lists.tasks.create', $list) }}" class="button button--primary">Nuova nota</a>
            </div>
        </div>

        @include('tasks._filters', [
            'list' => $list,
            'currentFilter' => $currentFilter ?? 'all',
            'filterRoute' => 'lists.show',
        ])

        @include('tasks._notes-grid', [
            'tasks' => $tasks,
            'emptyTitle' => match ($currentFilter ?? 'all') {
                'open' => 'Nessuna nota da fare',
                'done' => 'Nessuna nota completata',
                default => 'Questa lista è vuota',
            },
            'emptyDescription' => match ($currentFilter ?? 'all') {
                'open' => 'Qui appariranno le note ancora aperte.',
                'done' => 'Completa una nota per ritrovarla qui.',
                default => 'Crea una nota dentro questa lista.',
            },
        ])
    </section>
@endsection
