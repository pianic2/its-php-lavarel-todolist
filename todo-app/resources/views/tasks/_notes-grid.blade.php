@if ($tasks->count())
    <div class="notes-board">
        @foreach ($tasks as $task)
            <article class="card note-card {{ $task->is_completed ? 'note-card--done card--outlined' : 'note-card--open card--elevated' }}">
                <div class="card__body">
                    <div class="note-card__meta">
                        <span class="badge {{ $task->is_completed ? 'badge--success' : 'badge--warning' }}">
                            {{ $task->is_completed ? 'Completata' : 'Da fare' }}
                        </span>
                    </div>

                    <div>
                        <h3 class="card__title note-card__title">
                            <a href="{{ route('lists.tasks.show', [$task->list, $task]) }}">{{ $task->title }}</a>
                        </h3>
                        <p class="card__description note-card__description">
                            {{ \Illuminate\Support\Str::words($task->description ?: 'Nessun contenuto. Questa nota usa un fallback pulito per restare leggibile.', 18, '…') }}
                        </p>
                    </div>
                </div>

                <div class="note-card__footer">
                    <small class="u-text-muted">{{ $task->created_at?->format('d/m/Y H:i') }}</small>

                    <div class="note-card__actions">
                        <form method="POST" action="{{ route('lists.tasks.toggle', [$task->list, $task]) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="button button--secondary button--sm">
                                {{ $task->is_completed ? 'Riapri' : 'Completa' }}
                            </button>
                        </form>

                        <a href="{{ route('lists.tasks.edit', [$task->list, $task]) }}" class="button button--outline button--sm">Modifica</a>

                        <form method="POST" action="{{ route('lists.tasks.destroy', [$task->list, $task]) }}" onsubmit="return confirm('Eliminare questa nota?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button--danger button--sm">Elimina</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="u-mt-4">
        {{ $tasks->links() }}
    </div>
@else
    <div class="card note-empty">
        <div class="card__body">
            <h3 class="card__title">{{ $emptyTitle ?? 'Nessuna nota trovata' }}</h3>
            <p class="card__description">{{ $emptyDescription ?? 'Crea una nuova nota per iniziare.' }}</p>
        </div>
    </div>
@endif
