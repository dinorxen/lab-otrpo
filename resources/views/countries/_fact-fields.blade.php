@php($field = 'facts.'.$index)

<fieldset class="border rounded p-3 mb-3" data-fact-index="{{ $index }}">
    <legend class="h6 float-none w-auto px-1">Новый факт</legend>
    @error($field)
        <p class="text-danger">{{ $message }}</p>
    @enderror
    <div class="mb-3">
        <label class="form-label" for="fact-year-{{ $index }}">Год</label>
        <input class="form-control @error($field.'.year') is-invalid @enderror" id="fact-year-{{ $index }}"
               type="number" name="facts[{{ $index }}][year]" min="-9999" max="9999" step="1" required
               value="{{ is_scalar($fact['year'] ?? null) ? $fact['year'] : '' }}">
        <div class="invalid-feedback">
            @error($field.'.year') {{ $message }} @else Введите год. @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="fact-text-{{ $index }}">Текст</label>
        <textarea class="form-control @error($field.'.text') is-invalid @enderror" id="fact-text-{{ $index }}"
                  name="facts[{{ $index }}][text]" rows="3" maxlength="5000" required>{{ is_string($fact['text'] ?? null) ? $fact['text'] : '' }}</textarea>
        <div class="invalid-feedback">
            @error($field.'.text') {{ $message }} @else Введите текст факта. @enderror
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="fact-photo-{{ $index }}">Фото (необязательно)</label>
        <input class="form-control @error($field.'.photo') is-invalid @enderror" id="fact-photo-{{ $index }}"
               type="file" name="facts[{{ $index }}][photo]" accept="image/jpeg,image/png,image/webp">
        @error($field.'.photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button class="btn btn-outline-danger btn-sm" type="button" data-remove-fact>Убрать факт из формы</button>
</fieldset>
