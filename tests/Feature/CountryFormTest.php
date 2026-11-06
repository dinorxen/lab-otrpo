<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CountryFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_facts_can_be_added_when_creating_and_updating_a_country(): void
    {
        $disk = Storage::fake('public');
        $fields = [
            'name' => 'Казахстан',
            'government_form' => 'Республика',
            'area' => '2724900',
            'description' => str_repeat('А', Country::DESCRIPTION_MIN),
        ];

        $this->get(route('countries.create'))->assertOk()
            ->assertSee('value="Казахстан"', false)->assertSee('inputmode="numeric"', false)
            ->assertSee('id="add-country-fact"', false)
            ->assertDontSee('href="#saved-country-facts"', false);
        $this->assertGreaterThanOrEqual(20, count((new Country)->countryNameOptions()));

        $this->post(route('countries.store'), $fields + ['facts' => [
            ['year' => 1991, 'text' => 'Принят закон о государственной независимости.'],
            ['year' => 1992, 'text' => 'Казахстан вступил в ООН.', 'photo' => UploadedFile::fake()->createWithContent(
                'event.png', file_get_contents(base_path('tests/Fixtures/large-flag.png'))
            )],
        ]])->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();

        $country = Country::query()->sole();
        $original = $country->historicalFacts()->where('year', 1992)->sole();
        $disk->assertExists($original->photo_path);

        $this->put(route('countries.update', $country), $fields + ['facts' => [
            2 => ['year' => 1993, 'text' => 'Введена национальная валюта.'],
        ]])->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();

        $this->assertSame(3, $country->historicalFacts()->count());
        $this->assertModelExists($original);
        $this->get(route('countries.show', $country))->assertOk()->assertSee('Введена национальная валюта.');
    }

    public function test_invalid_area_or_fact_prevents_saving_the_country(): void
    {
        $fields = [
            'name' => 'Канада',
            'government_form' => 'Конституционная монархия',
            'area' => '100',
            'description' => str_repeat('А', Country::DESCRIPTION_MIN),
        ];

        foreach (['12.5', '-100', '+100', '1e3', '100км', '0', '2147483648'] as $area) {
            $this->postJson(route('countries.store'), array_replace($fields, ['area' => $area]))
                ->assertUnprocessable()->assertJsonValidationErrors('area');
        }
        $this->postJson(route('countries.store'), $fields + ['facts' => [
            ['year' => 0, 'text' => 'Некорректный год.'],
        ]])->assertUnprocessable()->assertJsonValidationErrors('facts.0.year');
        $this->assertDatabaseCount('countries', 0);

        $country = Country::create($fields);
        $this->putJson(route('countries.update', $country), array_replace($fields, ['area' => '101', 'facts' => [
            ['year' => 1867, 'text' => 'Текст останется в форме при ошибке.'],
            ['year' => 0, 'text' => 'Некорректный год.'],
        ]]))->assertUnprocessable()->assertJsonValidationErrors('facts.1.year');
        $this->assertSame(100, (int) $country->refresh()->area);
        $this->assertDatabaseCount('historical_facts', 0);
    }

    public function test_invalid_array_values_can_be_redisplayed_as_validation_errors(): void
    {
        $form = route('countries.create');
        $this->from($form)->post(route('countries.store'), [
            'name' => 'Канада',
            'government_form' => 'Конституционная монархия',
            'area' => ['100'],
            'description' => ['Недопустимое значение.'],
        ])->assertRedirect($form)->assertSessionHasErrors(['area', 'description']);

        $this->get($form)->assertOk()->assertSee('is-invalid');
    }
}
