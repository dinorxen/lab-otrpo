<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryNameValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_country_names_reject_digits_and_special_characters_on_create_and_update(): void
    {
        $fields = ['name' => 'Франция', 'government_form' => 'Республика', 'area' => 100];
        $country = Country::create($fields);

        foreach (['Франция!', 'Франция2', 'Франция/Италия', 'Франция🇫🇷', '-Франция', 'Франция--Италия'] as $name) {
            $data = array_replace($fields, ['name' => $name]);

            $this->postJson(route('countries.store'), $data)->assertUnprocessable()->assertJsonValidationErrors('name');
            $this->putJson(route('countries.update', $country), $data)->assertUnprocessable()->assertJsonValidationErrors('name');
        }

        $this->assertDatabaseCount('countries', 1);
        $this->assertSame('Франция', $country->refresh()->name);
    }

    public function test_country_names_accept_letters_and_separators_between_them(): void
    {
        foreach (['Новая Зеландия', 'Гвинея-Бисау', "Кот-д'Ивуар", 'Côte d’Ivoire'] as $name) {
            $this->post(route('countries.store'), [
                'name' => $name,
                'government_form' => 'Республика',
                'area' => 100,
                'description' => str_repeat('А', Country::DESCRIPTION_MIN),
            ])
                ->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();
            $this->assertDatabaseHas('countries', ['name' => $name]);
        }
    }

    public function test_duplicate_names_are_rejected_except_for_the_country_being_edited(): void
    {
        $data = [
            'name' => 'Новая Зеландия',
            'government_form' => 'Конституционная монархия',
            'area' => 100,
            'description' => str_repeat('А', Country::DESCRIPTION_MIN),
        ];
        $country = Country::create($data);
        $otherCountry = Country::create(array_replace($data, ['name' => 'Австрия']));
        $duplicate = array_replace($data, ['name' => 'новая  зеландия']);

        $this->getJson(route('countries.check-name', ['name' => $duplicate['name']]))
            ->assertUnprocessable()->assertJsonValidationErrors('name');
        $this->getJson(route('countries.check-name', ['name' => $duplicate['name'], 'country_id' => $country->id]))
            ->assertOk()->assertJson(['available' => true]);
        $this->postJson(route('countries.store'), $duplicate)->assertUnprocessable()->assertJsonValidationErrors('name');
        $this->putJson(route('countries.update', $otherCountry), $duplicate)
            ->assertUnprocessable()->assertJsonValidationErrors('name');
        $this->put(route('countries.update', $country), $duplicate)
            ->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();

        $country->delete();
        $this->post(route('countries.store'), $data)->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();
    }

    public function test_database_rejects_a_duplicate_when_form_validation_is_bypassed(): void
    {
        $fields = ['name' => 'Кот-д’Ивуар', 'government_form' => 'Республика', 'area' => 100];
        Country::create($fields);

        $this->expectException(UniqueConstraintViolationException::class);
        Country::create(array_replace($fields, ['name' => "КОТ-Д'ИВУАР"]));
    }

    public function test_new_description_and_government_form_are_validated_but_legacy_values_can_be_kept(): void
    {
        $legacy = ['name' => 'Франция', 'government_form' => 'Старое значение', 'area' => 100, 'description' => 'Короткое описание.'];
        $country = Country::create($legacy);
        $new = array_replace($legacy, ['name' => 'Италия']);

        $this->get(route('countries.edit', $country))->assertOk()->assertSee('Старое значение')->assertSee('rows="10"', false);
        $this->postJson(route('countries.store'), $new)->assertUnprocessable()->assertJsonValidationErrors(['government_form', 'description']);
        $this->put(route('countries.update', $country), array_replace($legacy, ['area' => 120]))
            ->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();
        $this->assertSame($legacy['description'], $country->refresh()->description);

        $this->putJson(route('countries.update', $country), array_replace($legacy, ['description' => 'Другой короткий текст.']))
            ->assertUnprocessable()->assertJsonValidationErrors('description');
        $this->postJson(route('countries.store'), array_replace($new, [
            'government_form' => 'Республика',
            'description' => str_repeat('А', Country::DESCRIPTION_MAX + 1),
        ]))->assertUnprocessable()->assertJsonValidationErrors('description');
    }
}
