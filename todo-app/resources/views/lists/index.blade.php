@extends('layouts.app')

@section('content')
    <section class="notes-page">
        <div class="notes-page__header">
            <div>
                <p class="app-eyebrow">Organizzazione</p>
                <h2>Tutte le liste</h2>
                <p class="u-text-muted">Ogni lista organizza le note. Una nota nasce sempre dentro una lista.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert--success">
                <div class="alert__content">
                    <div class="alert__title">Operazione completata</div>
                    <div class="alert__description">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if ($lists->count())
            <div class="notes-board">
                @foreach ($lists as $list)
                    <article class="card note-card card--elevated">
                        <div class="card__body">
                            <div class="note-card__meta">
                                <span class="badge badge--primary">Lista</span>
                                <span class="badge">{{ $list->tasks_count }} note</span>
                            </div>

                            <div>
                                <h3 class="card__title">
                                    <a href="{{ route('lists.show', $list) }}">{{ $list->name }}</a>
                                </h3>
                                <p class="card__description">
                                    {{ $list->description ?: 'Nessuna descrizione. Fallback attivo per mantenere il layout coerente.' }}
                                </p>
                            </div>
                        </div>

                        <div class="note-card__actions">
                            <a href="{{ route('lists.show', $list) }}" class="button button--secondary button--sm">Apri</a>
                            <a href="{{ route('lists.edit', $list) }}" class="button button--outline button--sm">Modifica</a>
                            <form method="POST" action="{{ route('lists.destroy', $list) }}" onsubmit="return confirm('Eliminare questa lista? Verranno eliminate anche le note associate.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button--danger button--sm">Elimina</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="u-mt-4">
                {{ $lists->links() }}
            </div>
        @else
            <div class="card note-empty">
                <div class="card__body">
                    <h3 class="card__title">Nessuna lista disponibile</h3>
                    <p class="card__description">Crea una lista per poter aggiungere la prima nota.</p>
                </div>
            </div>
        @endif
    </section>
@endsection
