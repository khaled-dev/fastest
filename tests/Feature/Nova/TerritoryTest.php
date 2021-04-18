<?php

namespace Tests\Feature\Nova;

use Tests\TestCase;
use App\Models\User;
use App\Models\Territory;
use NovaTesting\NovaAssertions;

class TerritoryTest extends TestCase
{
    use NovaAssertions;

    /**
     * @var array|null
     */
    private $territories = null;

    /**
     * @var string
     */
    private $resourceName = 'territories';

    /**
     * setup function for job tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->territories = Territory::factory(5)->create();
    }

    /**
     * @test
     */
    public function itCanNotListTerritories()
    {
        $this->get('/nova/territories/')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function itCanListTerritories()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->territories[0]->name_en)
            ->assertFieldsInclude('name_ar', $this->territories[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanShowTerritory()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->territories[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->territories[0]->name_en)
            ->assertFieldsInclude('name_ar', $this->territories[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanOpenTerritoriesCreateForm()
    {
        $this->be(User::factory()->create());
        $this->novaCreate($this->resourceName)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanStoreTerritory()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';

        $this->be(User::factory()->create())
            ->postJson('nova-api/territories/', [
                'name_ar' => $name_ar,
                'name_en' => $name_en,
            ])
            ->assertStatus(201);

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name_en', $name_en)
            ->assertFieldsInclude('name_ar', $name_ar);
    }

    /**
     * @test
     */
    public function itCanOpenTerritoriesEditForm()
    {
        $this->be(User::factory()->create());
        $this->novaEdit($this->resourceName, $this->territories[0]->id)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanUpdateTerritory()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';

        $this->be(User::factory()->create())
            ->putJson('nova-api/territories/' . $this->territories[0]->id, [
                'name_ar' => $name_ar,
                'name_en' => $name_en,
            ])
            ->assertOk();

        $this->novaDetail($this->resourceName, $this->territories[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $name_en)
            ->assertFieldsInclude('name_ar', $name_ar);
    }

    /**
     * @test
     */
    public function itCanNotDeleteTerritory()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotDelete();
    }

}
