<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HistoricalFactTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_fact_and_its_photo_can_be_managed_from_the_country_edit_page(): void
    {
        $disk = Storage::fake('public');
        $fields = ['name' => 'Италия', 'government_form' => 'Республика', 'area' => 301340];
        $country = Country::create($fields + ['description' => 'Описание Италии.']);
        $image = file_get_contents(base_path('tests/Fixtures/large-flag.png'));

        $this->put(route('countries.update', $country), $fields + ['facts' => [[
            'year' => -753,
            'text' => 'Традиционная дата основания Рима.',
            'photo' => UploadedFile::fake()->createWithContent('rome.png', $image),
        ]]])->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();

        $fact = $country->historicalFacts()->sole();
        $originalPhoto = $fact->photo_path;
        $disk->assertExists($originalPhoto);
        $editUrl = route('countries.edit', $country);
        $savedFactsUrl = $editUrl.'#saved-country-facts';
        $factEditUrl = route('countries.historical-facts.edit', [$country, $fact]);
        $updateUrl = route('countries.historical-facts.update', [$country, $fact]);
        $this->get(route('countries.show', $country))->assertOk()->assertSee('Описание Италии.')
            ->assertSee('753 г. до н. э.')->assertSee($originalPhoto)
            ->assertDontSee('Форма правления:')->assertDontSee('Площадь:')
            ->assertDontSee('Добавить факт')->assertDontSee($factEditUrl, false)->assertDontSee($updateUrl, false);
        $this->get(route('countries.show', $country), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertSee($fact->text)->assertDontSee($factEditUrl, false)->assertDontSee($updateUrl, false);
        $this->get($editUrl)->assertOk()->assertSee('Сохранённые факты')->assertSee('Добавить факт')->assertSee($factEditUrl, false)
            ->assertSee('action="'.$updateUrl.'"', false);
        $this->get($factEditUrl)->assertOk()->assertSee($fact->text)->assertSee($savedFactsUrl, false);

        $data = ['year' => 1870, 'text' => 'Рим вошёл в состав Королевства Италия.'];
        $this->put($updateUrl, $data)->assertRedirect($savedFactsUrl)->assertSessionHasNoErrors();
        $this->assertSame($originalPhoto, $fact->refresh()->photo_path);
        $this->assertSame($data['text'], $fact->text);

        $this->put($updateUrl, $data + ['photo' => UploadedFile::fake()->createWithContent('italy.png', $image)])
            ->assertRedirect($savedFactsUrl)->assertSessionHasNoErrors();
        $disk->assertMissing($originalPhoto);
        $newPhoto = $fact->refresh()->photo_path;
        $disk->assertExists($newPhoto);

        $this->delete(route('countries.historical-facts.destroy', [$country, $fact]))
            ->assertRedirect($savedFactsUrl);
        $this->assertModelMissing($fact);
        $disk->assertMissing($newPhoto);
    }

    public function test_a_fact_is_accessible_only_through_its_own_country(): void
    {
        $italy = Country::create(['name' => 'Италия', 'government_form' => 'Республика', 'area' => 301340]);
        $france = Country::create(['name' => 'Франция', 'government_form' => 'Республика', 'area' => 551695]);
        $fact = $italy->historicalFacts()->create(['year' => 1861, 'text' => 'Образование Королевства Италия.']);

        $this->get(route('countries.show', $france))->assertOk()->assertDontSee($fact->text);
        $this->get(route('countries.historical-facts.edit', [$france, $fact]))->assertNotFound();
        $this->put(route('countries.historical-facts.update', [$france, $fact]), ['year' => 1870, 'text' => 'Другой текст.'])
            ->assertNotFound();
        $this->delete(route('countries.historical-facts.destroy', [$france, $fact]))->assertNotFound();
        $this->assertModelExists($fact);
    }
}
