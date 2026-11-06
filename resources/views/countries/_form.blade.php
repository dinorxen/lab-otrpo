@csrf
@php
    $area = old('area', $country->area);
    $description = old('description', $country->description);
@endphp

<div class="mb-3">
    <label for="name" class="form-label">Название</label>
    <select class="form-select @error('name') is-invalid @enderror" id="name" name="name"
            data-name-check-url="{{ route('countries.check-name') }}" data-country-id="{{ $country->getKey() }}"
            aria-describedby="name-feedback" required autofocus>
        <option value="">Выберите страну</option>
        @foreach ($country->countryNameOptions() as $countryName)
            <option value="{{ $countryName }}" @selected(old('name', $country->name) === $countryName)>
                {{ $countryName }}
            </option>
        @endforeach
    </select>
    <div class="invalid-feedback" id="name-feedback" aria-live="polite">
        @error('name') {{ $message }} @else Выберите страну. @enderror
    </div>
</div>

<div class="mb-3">
    <label for="government_form" class="form-label">Форма правления</label>
    <select class="form-select @error('government_form') is-invalid @enderror"
            id="government_form" name="government_form" required>
        <option value="">Выберите форму правления</option>
        @foreach ($country->governmentFormOptions() as $formOption)
            <option value="{{ $formOption }}" @selected(old('government_form', $country->government_form) === $formOption)>
                {{ $formOption }}
            </option>
        @endforeach
    </select>
    <div class="invalid-feedback">
        @error('government_form') {{ $message }} @else Выберите форму правления. @enderror
    </div>
</div>

<div class="mb-3">
    <label for="area" class="form-label">Площадь, км²</label>
    <input type="text" inputmode="numeric" pattern="[0-9]+"
           class="form-control @error('area') is-invalid @enderror"
           id="area" name="area" aria-describedby="area-feedback"
           data-max="{{ \App\Models\Country::AREA_MAX }}" value="{{ is_scalar($area) ? $area : '' }}" required>
    <div class="invalid-feedback" id="area-feedback" aria-live="polite">
        @error('area') {{ $message }} @else Введите площадь целым числом, больше нуля. @enderror
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Описание</label>
    <textarea class="form-control @error('description') is-invalid @enderror"
              id="description" name="description" rows="10" aria-describedby="description-feedback description-count"
              data-min="{{ \App\Models\Country::DESCRIPTION_MIN }}" data-max="{{ \App\Models\Country::DESCRIPTION_MAX }}"
              data-original="{{ $country->description }}"
              @required(!$country->exists)>{{ is_string($description) ? $description : '' }}</textarea>
    <div class="invalid-feedback" id="description-feedback" aria-live="polite">
        @error('description') {{ $message }} @else Введите хотя бы {{ \App\Models\Country::DESCRIPTION_MIN }} символов. @enderror
    </div>
    <div class="form-text" id="description-count">
        От {{ \App\Models\Country::DESCRIPTION_MIN }} до {{ number_format(\App\Models\Country::DESCRIPTION_MAX, 0, ',', ' ') }} символов.
    </div>
</div>

<div class="mb-3">
    <label for="flag" class="form-label">Флаг</label>
    @if (!empty($country->flag_path))
        <img src="{{ asset('storage/' . $country->flag_path) }}" width="120"
             class="img-fluid d-block mb-2" alt="Текущий флаг">
    @endif
    <input type="file" id="flag" name="flag" accept="image/jpeg,image/png,image/webp"
           class="form-control @error('flag') is-invalid @enderror" aria-describedby="flag-help">
    <div id="flag-help" class="form-text">JPEG, PNG или WebP до 2 МБ. Оставьте поле пустым, чтобы сохранить текущий флаг.</div>
    @error('flag')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<section id="country-facts" class="mb-4" tabindex="-1" aria-labelledby="country-facts-title">
    <h2 class="h4" id="country-facts-title">Добавление исторических фактов</h2>
    <p class="form-text">Факты сохранятся вместе со страной. Год до нашей эры указывай со знаком минус.</p>
    @if ($country->exists)
        <p><a href="#saved-country-facts">Перейти к сохранённым фактам</a></p>
    @endif
    @error('facts')
        <p class="text-danger">{{ $message }}</p>
    @enderror
    <div id="country-fact-rows">
        @foreach (is_array(old('facts')) ? old('facts') : [] as $index => $fact)
            @include('countries._fact-fields', ['index' => $index, 'fact' => is_array($fact) ? $fact : []])
        @endforeach
    </div>
    <template id="country-fact-template">
        @include('countries._fact-fields', ['index' => '__INDEX__', 'fact' => []])
    </template>
    <button class="btn btn-outline-primary" type="button" id="add-country-fact">Добавить факт</button>
</section>
