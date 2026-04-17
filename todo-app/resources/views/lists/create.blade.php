@extends('layouts.app')

@section('content')
    <div class="container container--sm">
        <div class="u-mb-4">
            <p class="app-eyebrow">Nuova lista</p>
            <h2>Crea una lista</h2>
            <p class="u-text-muted">Le note potranno essere raggruppate qui, ma il sistema continuerà a funzionare anche senza associazioni.</p>
        </div>

        @include('lists._form', [
            'list' => $list,
            'action' => route('lists.store'),
            'method' => 'POST',
            'submitLabel' => 'Crea lista',
        ])
    </div>
@endsection
