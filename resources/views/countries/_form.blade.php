@csrf

<div class="mb-3">
    <label for="name" class="form-label">Название</label>
    <input type="text"
           class="form-control @error('name') is-invalid @enderror"
           id="name" name="name"
           value="{{ old('name', $country->name ?? '') }}"
           maxlength="255" required>
    <div class="invalid-feedback">
        @error('name') {{ $message }} @else Введите название страны. @enderror
    </div>
</div>

<div class="mb-3">
    <label for="government_form" class="form-label">Форма правления</label>
    <input type="text"
           class="form-control @error('government_form') is-invalid @enderror"
           id="government_form" name="government_form"
           value="{{ old('government_form', $country->government_form ?? '') }}"
           maxlength="255" required>
    <div class="invalid-feedback">
        @error('government_form') {{ $message }} @else Введите форму правления. @enderror
    </div>
</div>

<div class="mb-3">
    <label for="area" class="form-label">Площадь, км²</label>
    <input type="number"
           class="form-control @error('area') is-invalid @enderror"
           id="area" name="area"
           value="{{ old('area', $country->area ?? '') }}"
           min="1" step="1" required>
    <div class="invalid-feedback">
        @error('area') {{ $message }} @else Введите площадь целым числом, больше нуля. @enderror
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Описание</label>
    <textarea class="form-control @error('description') is-invalid @enderror"
              id="description" name="description" rows="5">{{ old('description', $country->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
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

<script>
    // Проверка полей средствами HTML5 и Bootstrap: подсвечивает ошибки до отправки на сервер
    var forms = document.querySelectorAll('.needs-validation');
    for (var i = 0; i < forms.length; i++) {
        forms[i].addEventListener('submit', function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    }
</script>
