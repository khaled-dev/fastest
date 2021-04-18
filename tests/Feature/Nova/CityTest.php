<?php

namespace Tests\Feature\Nova;

use App\Models\Territory;
use Tests\TestCase;
use App\Models\User;
use App\Models\City;
use NovaTesting\NovaAssertions;

class CityTest extends TestCase
{
    use NovaAssertions;

    /**
     * @var array|null
     */
    private $cities = null;

    /**
     * @var string
     */
    private $resourceName = 'cities';

    /**
     * setup function for city tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->cities = City::factory(5)->create();
    }

    /**
     * @test
     */
    public function itCanNotListCities()
    {
        $this->get('/nova/cities/')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function itCanListCities()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->cities[0]->name_en)
            ->assertFieldsInclude('territory', $this->cities[0]->territory->name_ar)
            ->assertFieldsInclude('name_ar', $this->cities[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanShowTerritory()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->cities[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->cities[0]->name_en)
            ->assertFieldsInclude('territory', $this->cities[0]->territory->name_ar)
            ->assertFieldsInclude('name_ar', $this->cities[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanOpenCitiesCreateForm()
    {
        $this->be(User::factory()->create());
        $this->novaCreate($this->resourceName)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanStoreCity()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';
        $territory = Territory::factory()->create();

        $this->be(User::factory()->create())
            ->postJson('nova-api/cities/', [
                'name_ar' => $name_ar,
                'name_en' => $name_en,
                'territory' => $territory->id,
            ])
            ->assertStatus(201);

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name_en', $name_en)
            ->assertFieldsInclude('name_en', $name_en)
            ->assertFieldsInclude('territory', $territory->name_ar);
    }

    /**
     * @test
     */
    public function itCanOpenCitiesEditForm()
    {
        $this->be(User::factory()->create());
        $this->novaEdit($this->resourceName, $this->cities[0]->id)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanUpdateCity()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';
        $territory = Territory::factory()->create();

        $this->be(User::factory()->create())
            ->putJson('nova-api/cities/' . $this->cities[0]->id, [
                'name_ar' => $name_ar,
                'name_en' => $name_en,
                'territory' => $territory->id,
            ])
            ->assertOk();

        $this->novaDetail($this->resourceName, $this->cities[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $name_en)
            ->assertFieldsInclude('name_ar', $name_ar)
            ->assertFieldsInclude('territory', $territory->name_ar);
    }

    /**
     * @test
     */
    public function itCanNotDeleteCity()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotDelete();
    }
}
