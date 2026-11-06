@if ($country->description)
    <p>{!! nl2br(e($country->description)) !!}</p>
@else
    <p class="text-muted">Описание пока не добавлено.</p>
@endif
