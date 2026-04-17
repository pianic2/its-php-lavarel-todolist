@php
    $isEditing = isset($method) && strtoupper($method) !== 'POST';
@endphp

<form method="POST" action="{{ $action }}" class="card form">
    @csrf

    @if ($isEditing)
        @method($method)
    @endif

    <div class="form__group">
        <label for="title" class="form__label">Titolo</label>
        <input
            type="text"
            id="title"
            name="title"
            class="form__control @error('title') form__control--error @enderror"
            value="{{ old('title', $task->title ?? '') }}"
            placeholder="Scrivi il titolo della nota"
            required
        >
        @error('title')
            <p class="form__error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form__group">
        <label for="description" class="form__label">Descrizione</label>
        <textarea
            id="description"
            name="description"
            class="form__textarea @error('description') form__textarea--error @enderror"
            rows="5"
            placeholder="Descrizione opzionale"
        >{{ old('description', $task->description ?? '') }}</textarea>
        @error('description')
            <p class="form__error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form__group">
        <span class="form__label">Lista</span>
        <p class="form__hint">{{ $list->name }}</p>
    </div>

    <div class="form__group">
        <label class="u-inline-flex u-items-center u-gap-2">
            <input
                type="hidden"
                name="is_completed"
                value="0"
            >
            <input
                type="checkbox"
                name="is_completed"
                value="1"
                {{ old('is_completed', $task->is_completed ?? false) ? 'checked' : '' }}
            >
            <span>Nota completata</span>
        </label>
        @error('is_completed')
            <p class="form__error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form__actions">
        <a href="{{ route('lists.show', $list) }}" class="button button--ghost">Annulla</a>
        <button type="submit" class="button button--primary">
            {{ $submitLabel ?? 'Salva nota' }}
        </button>
    </div>
</form>
