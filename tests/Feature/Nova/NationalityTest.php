<?php

namespace Tests\Feature\Nova;

use Tests\TestCase;
use App\Models\User;
use App\Models\Nationality;
use NovaTesting\NovaAssertions;

class NationalityTest extends TestCase
{
    use NovaAssertions;

    /**
     * @var array|null
     */
    private $nationalities = null;

    /**
     * @var string
     */
    private $resourceName = 'nationalities';

    /**
     * setup function for job tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->nationalities = Nationality::factory(5)->create();
    }

    /**
     * @test
     */
    public function itCanNotListNationalities()
    {
        $this->get('/nova/nationalities/')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function itCanListNationalities()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->nationalities[0]->name_en)
            ->assertFieldsInclude('name_ar', $this->nationalities[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanShowNationality()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->nationalities[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->nationalities[0]->name_en)
            ->assertFieldsInclude('name_ar', $this->nationalities[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanOpenNationalitiesCreateForm()
    {
        $this->be(User::factory()->create());
        $this->novaCreate($this->resourceName)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanStoreNationality()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';

        $this->be(User::factory()->create())
            ->postJson('nova-api/nationalities/', [
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
    public function itCanOpenNationalitiesEditForm()
    {
        $this->be(User::factory()->create());
        $this->novaEdit($this->resourceName, $this->nationalities[0]->id)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanUpdateNationality()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';

        $this->be(User::factory()->create())
            ->putJson('nova-api/nationalities/' . $this->nationalities[0]->id, [
                'name_ar' => $name_ar,
                'name_en' => $name_en,
            ])
            ->assertOk();

        $this->novaDetail($this->resourceName, $this->nationalities[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $name_en)
            ->assertFieldsInclude('name_ar', $name_ar);
    }

    /**
     * @test
     */
    public function itCanNotDeleteNationality()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotDelete();
    }

}
