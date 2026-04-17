<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel TODO') }}</title>

    <link rel="stylesheet" href="{{ asset('css/core.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/project.css') }}">
</head>
<body>
    @php
        $lists = $sidebarLists ?? collect();
        $activeListId = request()->route('list')?->id;
    @endphp

    <input type="checkbox" id="sidebar-toggle" class="app-sidebar-toggle" aria-hidden="true">

    <div class="app-shell">
        <header class="app-topbar">
            <div class="app-topbar__start">
                <label for="sidebar-toggle" class="app-menu-button" aria-label="Apri menu liste">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>

                <div>
                    <p class="app-eyebrow">Workspace note</p>
                    <h1 class="app-title">{{ config('app.name', 'Laravel TODO') }}</h1>
                </div>
            </div>
        </header>

        <label for="sidebar-toggle" class="app-drawer-overlay" aria-hidden="true"></label>

        <div class="app-layout">
            <aside class="app-sidebar">
                <div class="app-sidebar__header">
                    <div>
                        <p class="app-eyebrow">Navigazione</p>
                        <h2 class="app-sidebar__title">Liste note</h2>
                    </div>

                    <label for="sidebar-toggle" class="app-sidebar__close" aria-label="Chiudi menu">×</label>
                </div>

                <div class="app-sidebar__actions">
                    <a href="{{ route('lists.index') }}" class="button button--secondary button--sm u-w-full">Tutte le liste</a>
                    <a href="{{ route('lists.create') }}" class="button button--primary button--sm u-w-full">Nuova lista</a>
                </div>

                <nav class="sidebar-nav" aria-label="Liste note">
                    @forelse ($lists as $list)
                        <a href="{{ route('lists.show', $list) }}" class="button button--sm {{ $activeListId === $list->id ? 'button--secondary is-active' : 'button--ghost' }}">
                            <span class="sidebar-item__text">{{ $list->name }}</span>
                            <span class="badge {{ $activeListId === $list->id ? 'badge--primary' : '' }}">{{ $list->tasks_count }}</span>
                        </a>
                    @empty
                        <div class="sidebar-empty card card--compact">
                            <h3 class="card__title">Nessuna lista</h3>
                            <p class="card__description">Crea la prima lista per organizzare le note.</p>
                        </div>
                    @endforelse
                </nav>
            </aside>

            <main class="app-main">
                @if (session('warning'))
                    <div class="alert alert--warning u-mb-4">
                        <div class="alert__content">
                            <div class="alert__title">Attenzione</div>
                            <div class="alert__description">{{ session('warning') }}</div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
