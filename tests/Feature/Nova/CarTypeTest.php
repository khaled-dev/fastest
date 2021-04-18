<?php

namespace Tests\Feature\Nova;

use Tests\TestCase;
use App\Models\User;
use App\Models\CarType;
use NovaTesting\NovaAssertions;

class CarTypeTest extends TestCase
{
    use NovaAssertions;

    /**
     * @var array|null
     */
    private $carTypes = null;

    /**
     * @var string
     */
    private $resourceName = 'car-types';

    /**
     * setup function for job tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->carTypes = CarType::factory(5)->create();
    }

    /**
     * @test
     */
    public function itCanNotListCarTypes()
    {
        $this->get('/nova/car-types/')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function itCanListCarTypes()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->carTypes[0]->name_en)
            ->assertFieldsInclude('name_ar', $this->carTypes[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanShowCarType()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->carTypes[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->carTypes[0]->name_en)
            ->assertFieldsInclude('name_ar', $this->carTypes[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanNotOpenCarTypesCreateForm()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotDelete();
    }

    /**
     * @test
     */
    public function itCanOpenCarTypesEditForm()
    {
        $this->be(User::factory()->create());
        $this->novaEdit($this->resourceName, $this->carTypes[0]->id)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanUpdateCarType()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';

        $this->be(User::factory()->create())
            ->putJson('nova-api/car-types/' . $this->carTypes[0]->id, [
                'name_ar' => $name_ar,
                'name_en' => $name_en,
            ])
            ->assertOk();

        $this->novaDetail($this->resourceName, $this->carTypes[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $name_en)
            ->assertFieldsInclude('name_ar', $name_ar);
    }

    /**
     * @test
     */
    public function itCanNotDeleteCarType()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotDelete();
    }

}
