<?php

namespace Tests\Feature\Nova;

use Tests\TestCase;
use App\Models\User;
use App\Models\Bank;
use NovaTesting\NovaAssertions;

class BankTest extends TestCase
{
    use NovaAssertions;

    /**
     * @var array|null
     */
    private $banks = null;

    /**
     * @var string
     */
    private $resourceName = 'banks';

    /**
     * setup function for job tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->banks = Bank::factory(5)->create();
    }

    /**
     * @test
     */
    public function itCanNotListBanks()
    {
        $this->get('/nova/banks/')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function itCanListBanks()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->banks[0]->name_en)
            ->assertFieldsInclude('name_ar', $this->banks[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanShowBank()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->banks[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $this->banks[0]->name_en)
            ->assertFieldsInclude('name_ar', $this->banks[0]->name_ar);
    }

    /**
     * @test
     */
    public function itCanOpenBanksCreateForm()
    {
        $this->be(User::factory()->create());
        $this->novaCreate($this->resourceName)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanStoreBank()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';

        $this->be(User::factory()->create())
            ->postJson('nova-api/banks/', [
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
    public function itCanOpenBanksEditForm()
    {
        $this->be(User::factory()->create());
        $this->novaEdit($this->resourceName, $this->banks[0]->id)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanUpdateBank()
    {
        $name_ar = 'ar name test';
        $name_en = 'en name test';

        $this->be(User::factory()->create())
            ->putJson('nova-api/banks/' . $this->banks[0]->id, [
                'name_ar' => $name_ar,
                'name_en' => $name_en,
            ])
            ->assertOk();

        $this->novaDetail($this->resourceName, $this->banks[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name_en', $name_en)
            ->assertFieldsInclude('name_ar', $name_ar);
    }

    /**
     * @test
     */
    public function itCanNotDeleteSkill()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotDelete();
    }

}
