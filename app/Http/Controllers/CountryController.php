<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\HistoricalFact;
use Closure;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class CountryController extends Controller
{
    private const NAME_FORMAT_MESSAGE = 'Введите название буквами. Между буквами допустимы пробел, дефис и апостроф.';

    private const DUPLICATE_NAME_MESSAGE = 'Страна с таким названием уже есть в каталоге.';

    private function rules(Country $country, bool $keepDescription): array
    {
        $rules = [
            'name' => $this->nameRules($country->getKey()),
            'government_form' => ['required', 'string', Rule::in($country->governmentFormOptions())],
            'area' => ['bail', 'required', 'regex:/\A[0-9]+\z/', 'numeric', 'min:1', 'max:'.Country::AREA_MAX],
            'description' => $keepDescription ? ['nullable', 'string'] : [
                'required', 'string', 'min:'.Country::DESCRIPTION_MIN, 'max:'.Country::DESCRIPTION_MAX,
            ],
            'flag' => 'bail|nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=4096,max_height=4096',
            'facts' => 'sometimes|array',
            'facts.*' => 'required|array:year,text,photo',
        ];

        foreach (HistoricalFact::FORM_RULES as $field => $rule) {
            $rules['facts.*.'.$field] = $rule;
        }

        return $rules;
    }

    private function nameRules(?int $exceptCountryId = null): array
    {
        return [
            'bail', 'required', 'string', 'max:255', 'regex:/\A'.Country::NAME_PATTERN.'\z/u',
            function (string $attribute, string $value, Closure $fail) use ($exceptCountryId): void {
                $countries = Country::query()->where('name_key', Country::nameKey($value));

                if ($exceptCountryId !== null) {
                    $countries->whereKeyNot($exceptCountryId);
                }

                if ($countries->exists()) {
                    $fail(self::DUPLICATE_NAME_MESSAGE);
                }
            },
        ];
    }

    public function checkName(Request $request): JsonResponse
    {
        $data = $request->validate(['country_id' => ['nullable', 'integer', 'min:1']]);
        $countryId = isset($data['country_id']) ? (int) $data['country_id'] : null;
        $request->validate(['name' => $this->nameRules($countryId)], ['name.regex' => self::NAME_FORMAT_MESSAGE]);

        return response()->json(['available' => true]);
    }

    // Список всех стран
    public function index()
    {
        $countries = Country::all();

        return view('countries.index', ['countries' => $countries]);
    }

    // Пустая форма добавления
    public function create()
    {
        return view('countries.create', ['country' => new Country]);
    }

    // Сохранение новой страны
    public function store(Request $request)
    {
        $this->saveCountry($request, new Country);

        return redirect()->route('countries.index')
            ->with('success', 'Страна добавлена');
    }

    // Страница одной страны. Для модального окна отдаём только блок подробностей
    public function show(Request $request, Country $country)
    {
        $country->load(['historicalFacts' => fn ($facts) => $facts->orderBy('year')->orderBy('id')]);

        if ($request->ajax()) {
            return view('countries._details', ['country' => $country]);
        }

        return view('countries.show', ['country' => $country]);
    }

    // Форма редактирования
    public function edit(Country $country)
    {
        $country->load(['historicalFacts' => fn ($facts) => $facts->orderBy('year')->orderBy('id')]);

        return view('countries.edit', ['country' => $country]);
    }

    // Сохранение изменений
    public function update(Request $request, Country $country)
    {
        $this->saveCountry($request, $country);

        return redirect()->route('countries.index')
            ->with('success', 'Страна обновлена');
    }

    // Удаление (мягкое, благодаря SoftDeletes в модели)
    public function destroy(Country $country)
    {
        $country->delete();

        return redirect()->route('countries.index')
            ->with('success', 'Страна удалена');
    }

    private function saveCountry(Request $request, Country $country): void
    {
        $description = $request->input('description');
        if (is_string($description)) {
            $description = str_replace("\r\n", "\n", $description);
            $request->merge(['description' => $description]);
        }

        $keepDescription = $country->exists && (
            $request->missing('description') ||
            ($description ?? '') === Str::trim(str_replace("\r\n", "\n", $country->description ?? ''))
        );

        $messages = [
            'name.regex' => self::NAME_FORMAT_MESSAGE,
            'government_form.required' => 'Выберите форму правления.',
            'government_form.in' => 'Выберите форму правления из списка.',
            'area.required' => 'Введите площадь.',
            'area.regex' => 'Площадь вводится только цифрами.',
            'area.min' => 'Площадь должна быть больше нуля.',
            'area.max' => 'Площадь должна быть не больше :max км².',
            'description.required' => 'Введите описание страны.',
            'description.min' => 'В описании должно быть не менее :min символов.',
            'description.max' => 'В описании должно быть не более :max символов.',
            'flag.uploaded' => 'Не удалось загрузить файл. Максимальный размер — 2 МБ.',
            'flag.image' => 'Выберите изображение в формате JPEG, PNG или WebP.',
            'flag.mimes' => 'Допустимые форматы флага: JPEG, PNG и WebP.',
            'flag.max' => 'Размер файла флага не должен превышать 2 МБ.',
            'flag.dimensions' => 'Ширина и высота флага не должны превышать 4096 пикселей.',
        ];
        foreach (HistoricalFact::FORM_MESSAGES as $field => $message) {
            $messages['facts.*.'.$field] = $message;
        }

        $data = $request->validate($this->rules($country, $keepDescription), $messages);
        $facts = $data['facts'] ?? [];
        unset($data['flag'], $data['facts']);
        if ($keepDescription) {
            unset($data['description']);
        }

        try {
            $this->persistCountry($country, $data, $request->file('flag'), $facts);
        } catch (UniqueConstraintViolationException $error) {
            if (!str_contains($error->getMessage(), 'countries_active_name_key_unique') &&
                !str_contains($error->getMessage(), 'countries.name_key')) {
                throw $error;
            }

            throw ValidationException::withMessages(['name' => self::DUPLICATE_NAME_MESSAGE]);
        }
    }

    private function persistCountry(Country $country, array $data, ?UploadedFile $flag, array $facts): void
    {
        $disk = Storage::disk('public');
        $uploadedPaths = [];
        $flagPath = null;

        try {
            if ($flag !== null) {
                $image = $this->resizeFlag($flag);
                $flagPath = 'flags/uploads/'.Str::uuid().'.png';
                $uploadedPaths[] = $flagPath;

                if (!$disk->put($flagPath, $image)) {
                    throw new RuntimeException('Не удалось сохранить флаг.');
                }
            }

            foreach ($facts as $index => $fact) {
                $photo = $fact['photo'] ?? null;
                unset($facts[$index]['photo']);
                if ($photo === null) {
                    continue;
                }

                $photoPath = $photo->store(HistoricalFact::PHOTO_DIRECTORY, 'public');
                if ($photoPath === false) {
                    throw ValidationException::withMessages([
                        "facts.$index.photo" => 'Не удалось сохранить фото. Попробуйте ещё раз.',
                    ]);
                }

                $uploadedPaths[] = $photoPath;
                $facts[$index]['photo_path'] = $photoPath;
            }

            $previousPath = DB::transaction(function () use ($country, $data, $flagPath, $facts) {
                if ($country->exists) {
                    $country = Country::query()->lockForUpdate()->findOrFail($country->getKey());
                }

                $previousPath = null;
                if ($flagPath !== null) {
                    $previousPath = $country->flag_path;
                    $country->flag_path = $flagPath;
                }

                $country->fill($data)->save();
                $country->historicalFacts()->createMany($facts);

                return $previousPath;
            });
        } catch (Throwable $error) {
            if ($uploadedPaths !== []) {
                $disk->delete($uploadedPaths);
            }

            throw $error;
        }

        if ($previousPath !== null && str_starts_with($previousPath, 'flags/uploads/')) {
            $disk->delete($previousPath);
        }
    }

    private function resizeFlag(UploadedFile $flag): string
    {
        $environment = [];
        if (PHP_OS_FAMILY === 'Windows' && ($systemRoot = getenv('SystemRoot', true)) !== false) {
            $environment['SystemRoot'] = $systemRoot;
        }

        $process = new Process(
            ['node', base_path('resources/js/resize-flag.cjs'), $flag->getPathname()],
            env: $environment,
        );
        $process->setTimeout(15);
        $process->run();

        if ($process->getExitCode() === 2) {
            throw ValidationException::withMessages(['flag' => 'Не удалось прочитать изображение флага.']);
        }

        if (!$process->isSuccessful() || $process->getOutput() === '') {
            throw new RuntimeException('Не удалось обработать флаг: '.$process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
