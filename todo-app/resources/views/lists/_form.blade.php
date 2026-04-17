<form method="POST" action="{{ $action }}" class="card form">
    @csrf

    @if (($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="form__group">
        <label for="name" class="form__label">Nome lista</label>
        <input
            type="text"
            id="name"
            name="name"
            class="form__control @error('name') form__control--error @enderror"
            value="{{ old('name', $list->name ?? '') }}"
            placeholder="Es. Lavoro, Casa, Idee"
            required
        >
        @error('name')
            <p class="form__error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form__group">
        <label for="description" class="form__label">Descrizione</label>
        <textarea
            id="description"
            name="description"
            class="form__textarea @error('description') form__textarea--error @enderror"
            rows="4"
            placeholder="Descrizione opzionale della lista"
        >{{ old('description', $list->description ?? '') }}</textarea>
        @error('description')
            <p class="form__error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form__actions">
        <a href="{{ route('lists.index') }}" class="button button--ghost">Annulla</a>
        <button type="submit" class="button button--primary">{{ $submitLabel ?? 'Salva lista' }}</button>
    </div>
</form>
