@extends('layouts.app')

@section('title', 'Страны')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h1 class="mb-0" id="countries">Страны</h1>
        <a href="{{ route('countries.create') }}" class="btn btn-primary">Добавить</a>
    </div>

    <div class="row g-4 country-grid">
        @foreach ($countries as $country)
            <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 country-column">
                <div class="card country-card h-100">
                    <div class="flag-area">
                        @if ($country->flag_path)
                            <img class="card-img-top img-fluid"
                                 src="{{ asset('storage/' . $country->flag_path) }}"
                                 alt="Флаг: {{ $country->name }}">
                        @endif
                        <span class="country-label">Страна</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h2 class="card-title h4">
                            <a class="text-reset" href="{{ route('countries.show', $country) }}">{{ $country->name }}</a>
                        </h2>
                        <p class="card-text mb-0 pb-4">
                            Форма правления: {{ $country->government_form }}.<br>
                            Площадь: {{ number_format($country->area, 0, ',', ' ') }} км²
                        </p>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-auto">
                            <button class="btn btn-primary details-button" type="button"
                                    data-url="{{ route('countries.show', $country) }}">Подробнее</button>
                            <a class="btn btn-outline-secondary" href="{{ route('countries.edit', $country) }}">Изменить</a>
                            <form action="{{ route('countries.destroy', $country) }}" method="POST"
                                  data-confirm="Удалить страну {{ $country->name }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
