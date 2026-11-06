<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class CountryController extends Controller
{
    // Правила проверки данных формы. Используются и при создании, и при изменении
    private function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'government_form' => 'required|string|max:255',
            'area' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'flag' => 'bail|nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:max_width=4096,max_height=4096',
        ];
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
        return view('countries.create');
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
        if ($request->ajax()) {
            return view('countries._details', ['country' => $country]);
        }

        return view('countries.show', ['country' => $country]);
    }

    // Форма редактирования
    public function edit(Country $country)
    {
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
        $data = $request->validate($this->rules(), [
            'flag.uploaded' => 'Не удалось загрузить файл. Максимальный размер — 2 МБ.',
            'flag.image' => 'Выберите изображение в формате JPEG, PNG или WebP.',
            'flag.mimes' => 'Допустимые форматы флага: JPEG, PNG и WebP.',
            'flag.max' => 'Размер файла флага не должен превышать 2 МБ.',
            'flag.dimensions' => 'Ширина и высота флага не должны превышать 4096 пикселей.',
        ]);
        $flag = $request->file('flag');
        unset($data['flag']);

        if ($flag === null) {
            $country->fill($data)->save();

            return;
        }

        $image = $this->resizeFlag($flag);
        $disk = Storage::disk('public');
        $path = 'flags/uploads/'.Str::uuid().'.png';

        try {
            if (!$disk->put($path, $image)) {
                throw new RuntimeException('Не удалось сохранить флаг.');
            }

            $previousPath = DB::transaction(function () use ($country, $data, $path) {
                if ($country->exists) {
                    $country = Country::query()->lockForUpdate()->findOrFail($country->getKey());
                }

                $previousPath = $country->flag_path;
                $country->fill([...$data, 'flag_path' => $path])->save();

                return $previousPath;
            });
        } catch (Throwable $error) {
            $disk->delete($path);

            throw $error;
        }

        if ($previousPath !== null && str_starts_with($previousPath, 'flags/uploads/')) {
            $disk->delete($previousPath);
        }
    }

    private function resizeFlag(UploadedFile $flag): string
    {
        $process = new Process(['node', base_path('resources/js/resize-flag.cjs'), $flag->getPathname()]);
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
