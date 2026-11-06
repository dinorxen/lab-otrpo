@php($factsSection = ($manageFacts ?? false) ? 'saved-country-facts' : 'historical-facts')

<section id="{{ $factsSection }}" class="mt-4" tabindex="-1" aria-labelledby="{{ $factsSection }}-title">
    <h2 id="{{ $factsSection }}-title" class="h4 mb-3">
        {{ ($manageFacts ?? false) ? 'Сохранённые факты' : 'Исторические факты' }}
    </h2>

    @forelse ($country->historicalFacts as $fact)
        <article class="border rounded p-3 mb-3">
            <h3 class="h5">{{ $fact->year < 0 ? abs($fact->year) . ' г. до н. э.' : $fact->year . ' г.' }}</h3>
            <p class="text-break">{!! nl2br(e($fact->text)) !!}</p>

            @if ($fact->photo_path)
                <img class="img-fluid rounded d-block mb-3" width="480" loading="lazy"
                     src="{{ asset('storage/' . $fact->photo_path) }}" alt="Фото к историческому факту">
            @endif

            @if ($manageFacts ?? false)
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-outline-primary btn-sm"
                       href="{{ route('countries.historical-facts.edit', [$country, $fact]) }}">Изменить</a>
                    <form method="POST" action="{{ route('countries.historical-facts.destroy', [$country, $fact]) }}"
                          data-confirm="Удалить этот исторический факт?">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm" type="submit">Удалить</button>
                    </form>
                </div>
            @endif
        </article>
    @empty
        <p class="text-muted mb-0">Исторических фактов пока нет.</p>
    @endforelse
</section>
