@extends('layouts.app')

@section('content')
    <section class="notes-page">
        <div class="notes-page__header">
            <div>
                <p class="app-eyebrow">Bacheca</p>
                <h2>Tutte le note</h2>
                <p class="u-text-muted">Lista: {{ $list->name }}</p>
            </div>

            <div class="notes-page__actions">
                <a href="{{ route('lists.tasks.create', $list) }}" class="button button--primary">Nuova nota</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert--success u-mb-4">
                <div class="alert__content">
                    <div class="alert__title">Operazione completata</div>
                    <div class="alert__description">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @include('tasks._filters', [
            'list' => $list,
            'currentFilter' => $currentFilter ?? 'all',
            'filterRoute' => 'lists.tasks.index',
        ])

        @include('tasks._notes-grid', [
            'tasks' => $tasks,
            'emptyTitle' => match ($currentFilter ?? 'all') {
                'open' => 'Nessuna nota da fare',
                'done' => 'Nessuna nota completata',
                default => 'Nessuna nota disponibile',
            },
            'emptyDescription' => match ($currentFilter ?? 'all') {
                'open' => 'Qui appariranno le note ancora aperte.',
                'done' => 'Completa una nota per ritrovarla qui.',
                default => 'Crea una nota: il sistema continuerà a funzionare anche senza liste associate.',
            },
            'emptyActionUrl' => route('lists.tasks.create', $list),
            'emptyActionLabel' => 'Crea una nota',
        ])
    </section>
@endsection
