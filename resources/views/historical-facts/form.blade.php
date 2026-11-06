@extends('layouts.app')

@section('title', 'Изменить исторический факт')

@section('content')
    @php
        $year = old('year', $fact->year);
        $text = old('text', $fact->text);
    @endphp
    <h1 class="mb-2">@yield('title')</h1>
    <p class="text-muted mb-4">{{ $country->name }}</p>

    <form method="POST" enctype="multipart/form-data"
          action="{{ route('countries.historical-facts.update', [$country, $fact]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="year" class="form-label">Год</label>
            <input id="year" name="year" type="number" min="-9999" max="9999" step="1" required
                   class="form-control @error('year') is-invalid @enderror"
                   value="{{ is_scalar($year) ? $year : '' }}" aria-describedby="year-help">
            @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text" id="year-help">Для года до нашей эры введи число со знаком минус.</div>
        </div>

        <div class="mb-3">
            <label for="text" class="form-label">Текст</label>
            <textarea id="text" name="text" rows="5" maxlength="5000" required
                      class="form-control @error('text') is-invalid @enderror">{{ is_string($text) ? $text : '' }}</textarea>
            @error('text')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Фото</label>
            @if ($fact->photo_path)
                <img class="img-fluid rounded d-block mb-2" width="160"
                     src="{{ asset('storage/' . $fact->photo_path) }}" alt="Текущее фото">
            @endif
            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp"
                   class="form-control @error('photo') is-invalid @enderror" aria-describedby="photo-help">
            @error('photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text" id="photo-help">JPEG, PNG или WebP до 2 МБ. Поле можно оставить пустым.</div>
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('countries.edit', $country) }}#saved-country-facts"
           class="btn btn-secondary">Отмена</a>
    </form>
@endsection
