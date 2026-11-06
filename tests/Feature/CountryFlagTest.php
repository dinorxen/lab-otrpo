<?php

namespace Tests\Feature;

use App\Models\Country;
use Database\Seeders\CountrySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CountryFlagTest extends TestCase
{
    use RefreshDatabase;

    private array $fields = [
        'name' => 'Тестовая страна',
        'government_form' => 'Республика',
        'area' => 120,
    ];

    public function test_a_flag_is_resized_and_can_be_replaced(): void
    {
        $disk = Storage::fake('public');
        $image = file_get_contents(base_path('tests/Fixtures/large-flag.png'));
        $fields = $this->fields + ['description' => str_repeat('А', Country::DESCRIPTION_MIN)];

        $this->post(route('countries.store'), $fields + [
            'flag' => UploadedFile::fake()->createWithContent('flag.png', $image),
        ])->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();

        $country = Country::query()->sole();
        $original = $country->flag_path;
        $disk->assertExists($original);
        [$width, $height] = getimagesize($disk->path($original));
        $this->assertSame([960, 640], [$width, $height]);
        $this->get(route('countries.show', $country), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->assertSee($original);

        $this->put(route('countries.update', $country), $fields)
            ->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();
        $this->assertSame($original, $country->refresh()->flag_path);

        $this->put(route('countries.update', $country), $fields + [
            'flag' => UploadedFile::fake()->createWithContent('replacement.png', $image),
        ])->assertRedirect(route('countries.index'))->assertSessionHasNoErrors();

        $country->refresh();
        $this->assertNotSame($original, $country->flag_path);
        $disk->assertExists($country->flag_path);
        $disk->assertMissing($original);
    }

    public function test_a_non_image_is_rejected(): void
    {
        $disk = Storage::fake('public');

        $this->from(route('countries.create'))->post(route('countries.store'), $this->fields + [
            'description' => str_repeat('А', Country::DESCRIPTION_MIN),
            'flag' => UploadedFile::fake()->createWithContent('flag.png', 'plain text'),
        ])->assertRedirect(route('countries.create'))->assertSessionHasErrors('flag');

        $this->assertDatabaseCount('countries', 0);
        $this->assertSame([], $disk->allFiles());
    }

    public function test_country_name_is_rendered_as_data_in_the_delete_form(): void
    {
        $name = 'Кот-д\'Ивуар "+(window.auditMarker=1)+"';
        $country = Country::create(array_replace($this->fields, ['name' => $name]));

        $this->get(route('countries.index'))
            ->assertOk()
            ->assertSee('href="'.route('countries.show', $country).'"', false)
            ->assertSee('data-confirm="'.e("Удалить страну $name?").'"', false)
            ->assertDontSee('onsubmit=', false);
    }

    public function test_country_seeding_preserves_existing_records(): void
    {
        $disk = Storage::fake('public');
        $france = Country::create(array_replace($this->fields, ['name' => 'Франция']));

        $this->seed(CountrySeeder::class);
        $this->assertDatabaseCount('countries', 5);
        $this->assertSame(120, $france->refresh()->area);
        $disk->assertExists(['flags/fr.png', 'flags/at.png', 'flags/nl.png', 'flags/jp.png', 'flags/it.png']);

        $italy = Country::where('name', 'Италия')->firstOrFail();
        $italy->delete();
        $this->seed(CountrySeeder::class);

        $this->assertDatabaseCount('countries', 5);
        $this->assertSoftDeleted($italy);
    }
}
