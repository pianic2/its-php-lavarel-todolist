@extends('layouts.app')

@section('content')
    <div class="container container--sm">
        <div class="u-mb-4 u-flex u-justify-between u-items-center">
            <div>
                <h2>{{ $task->title }}</h2>
                <p class="u-text-muted">Dettaglio nota</p>
            </div>

            <span class="badge {{ $task->is_completed ? 'badge--success' : 'badge--warning' }}">
                {{ $task->is_completed ? 'Completata' : 'Da fare' }}
            </span>
        </div>

        <div class="card">
            <div class="card__body">
                <div>
                    <h3 class="card__title">Lista</h3>
                    <p class="card__description">{{ $task->list?->name ?? 'Senza lista' }}</p>
                </div>

                @if ($task->description)
                    <div>
                        <h3 class="card__title">Descrizione</h3>
                        <p class="card__description">{{ $task->description }}</p>
                    </div>
                @endif

                <div>
                    <h3 class="card__title">Informazioni</h3>
                    <p class="card__description">ID nota: {{ $task->id }}</p>
                    <p class="card__description">ID lista: {{ $task->list_id ?? 'Nessuna lista' }}</p>
                    <p class="card__description">Creata il: {{ $task->created_at?->format('d/m/Y H:i') }}</p>
                    <p class="card__description">Aggiornata il: {{ $task->updated_at?->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="card__footer">
                <a href="{{ route('tasks.index') }}" class="button button--ghost">Indietro</a>
                <a href="{{ route('tasks.edit', $task) }}" class="button button--outline">Modifica</a>
                <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="button button--secondary">
                        {{ $task->is_completed ? 'Segna come aperta' : 'Segna come completata' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
