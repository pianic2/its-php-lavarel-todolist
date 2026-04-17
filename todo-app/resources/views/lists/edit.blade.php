@extends('layouts.app')

@section('content')
    <div class="container container--sm">
        <div class="u-mb-4">
            <p class="app-eyebrow">Modifica lista</p>
            <h2>{{ $list->name }}</h2>
            <p class="u-text-muted">Aggiorna i dati della lista senza perdere le note associate.</p>
        </div>

        @include('lists._form', [
            'list' => $list,
            'action' => route('lists.update', $list),
            'method' => 'PUT',
            'submitLabel' => 'Aggiorna lista',
        ])
    </div>
@endsection
