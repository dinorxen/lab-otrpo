<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\HistoricalFact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class HistoricalFactController extends Controller
{
    public function edit(Country $country, HistoricalFact $historicalFact): View
    {
        return view('historical-facts.form', ['country' => $country, 'fact' => $historicalFact]);
    }

    public function update(Request $request, Country $country, HistoricalFact $historicalFact): RedirectResponse
    {
        $this->save($request, $historicalFact);

        return redirect()->to(route('countries.edit', $country).'#saved-country-facts')
            ->with('success', 'Исторический факт обновлён');
    }

    public function destroy(Country $country, HistoricalFact $historicalFact): RedirectResponse
    {
        $photoPath = DB::transaction(function () use ($historicalFact) {
            $fact = HistoricalFact::query()->lockForUpdate()->findOrFail($historicalFact->getKey());
            $fact->delete();

            return $fact->photo_path;
        });

        if ($photoPath !== null && str_starts_with($photoPath, HistoricalFact::PHOTO_DIRECTORY.'/')) {
            Storage::disk('public')->delete($photoPath);
        }

        return redirect()->to(route('countries.edit', $country).'#saved-country-facts')
            ->with('success', 'Исторический факт удалён');
    }

    private function save(Request $request, HistoricalFact $fact): void
    {
        $data = $request->validate(HistoricalFact::FORM_RULES, HistoricalFact::FORM_MESSAGES);
        $photoPath = ($data['photo'] ?? null)?->store(HistoricalFact::PHOTO_DIRECTORY, 'public');
        unset($data['photo']);

        if ($photoPath === false) {
            throw ValidationException::withMessages(['photo' => 'Не удалось сохранить фото. Попробуйте ещё раз.']);
        }

        try {
            $oldPhoto = DB::transaction(function () use ($fact, $data, $photoPath) {
                $fact = HistoricalFact::query()->lockForUpdate()->findOrFail($fact->getKey());

                $oldPhoto = null;
                if ($photoPath !== null) {
                    $oldPhoto = $fact->photo_path;
                    $fact->photo_path = $photoPath;
                }

                $fact->fill($data)->save();

                return $oldPhoto;
            });
        } catch (Throwable $error) {
            if ($photoPath !== null) {
                Storage::disk('public')->delete($photoPath);
            }

            throw $error;
        }

        if ($oldPhoto !== null && str_starts_with($oldPhoto, HistoricalFact::PHOTO_DIRECTORY.'/')) {
            Storage::disk('public')->delete($oldPhoto);
        }
    }
}
