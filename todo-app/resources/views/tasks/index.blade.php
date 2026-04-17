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

        @include('tasks._notes-grid', [
            'tasks' => $tasks,
            'emptyTitle' => 'Nessuna nota disponibile',
            'emptyDescription' => 'Crea una nota: il sistema continuerà a funzionare anche senza liste associate.',
        ])
    </section>
@endsection
