@if ($country->flag_path)
    <img class="img-fluid d-block mx-auto mb-3 w-50"
         src="{{ asset('storage/' . $country->flag_path) }}"
         alt="Флаг: {{ $country->name }}">
@endif

<p><strong>Форма правления:</strong> {{ $country->government_form }}</p>
<p><strong>Площадь:</strong> {{ number_format($country->area, 0, ',', ' ') }} км²</p>

@if ($country->description)
    <p>{!! nl2br(e($country->description)) !!}</p>
@else
    <p class="text-muted">Описание пока не добавлено.</p>
@endif

<p class="text-muted small mb-0">Добавлено: {{ $country->created_at_formatted }}</p>
