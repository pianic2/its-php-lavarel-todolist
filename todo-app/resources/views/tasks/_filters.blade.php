@php
    $currentFilter = $currentFilter ?? 'all';
    $filterRoute = $filterRoute ?? 'lists.show';
    $filters = [
        'all' => 'Tutte',
        'open' => 'Da fare',
        'done' => 'Completate',
    ];
@endphp

<nav class="task-filters" aria-label="Filtra note">
    @foreach ($filters as $filter => $label)
        <a
            href="{{ route($filterRoute, $list) }}?filter={{ $filter }}"
            class="button button--sm {{ $currentFilter === $filter ? 'button--primary' : 'button--outline' }}"
            aria-current="{{ $currentFilter === $filter ? 'page' : 'false' }}"
        >
            {{ $label }}
        </a>
    @endforeach
</nav>
