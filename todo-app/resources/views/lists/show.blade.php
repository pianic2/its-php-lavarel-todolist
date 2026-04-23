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

            <div class="notes-page__share u-mt-4">
                <h3 class="u-text-sm">Condividi lista</h3>

                <form method="POST" action="{{ route('lists.share', $list) }}" class="u-inline-flex u-gap-2 u-mt-2">
                    @csrf
                    <input type="email" name="email" placeholder="Email utente" required style="padding:0.5rem;">
                    <button type="submit" class="button button--primary">Condividi</button>
                </form>

                @if($list->users->isNotEmpty())
                    <div class="u-mt-3">
                        <h4 class="u-text-sm">Collaboratori</h4>
                        <ul>
                            @foreach($list->users as $user)
                                <li class="u-inline-flex u-gap-2" style="align-items:center;">
                                    <span>{{ $user->email }} @if(auth()->id() === $user->id) (tu) @endif</span>
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('lists.unshare', [$list, $user]) }}" style="display:inline;margin-left:0.5rem;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button button--ghost">Rimuovi</button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
            'emptyActionUrl' => route('lists.tasks.create', $list),
            'emptyActionLabel' => 'Crea una nota',
        ])
    </section>
@endsection
