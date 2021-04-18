<?php

namespace Tests\Feature\Nova;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use NovaTesting\NovaAssertions;

class CustomerTest extends TestCase
{
    use NovaAssertions;

    /**
     * @var array|null
     */
    private $customers = null;

    /**
     * @var string
     */
    private $resourceName = 'customers';

    /**
     * setup function for job tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->customers = Customer::factory(5)->create();
    }

    /**
     * @test
     */
    public function itCanNotListCustomers()
    {
        $this->get('/nova/customers/')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function itCanListCustomers()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name', $this->customers[0]->name)
            ->assertFieldsInclude('mobile', $this->customers[0]->mobile);
    }

    /**
     * @test
     */
    public function itCanShowCustomer()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->customers[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name', $this->customers[0]->name)
            ->assertFieldsInclude('mobile', $this->customers[0]->mobile);
    }

    /**
     * @test
     */
    public function itCanOpenCustomersCreateForm()
    {
        $this->be(User::factory()->create());
        $this->novaCreate($this->resourceName)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanStoreCustomer()
    {
        $name = 'ar name test';
        $mobile = 'en name test';

        $this->be(User::factory()->create())
            ->postJson('nova-api/customers/', [
                'name' => $name,
                'mobile' => $mobile,
            ])
            ->assertStatus(201);

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name', $name)
            ->assertFieldsInclude('mobile', $mobile);
    }

    /**
     * @test
     */
    public function itCanOpenCustomersEditForm()
    {
        $this->be(User::factory()->create());
        $this->novaEdit($this->resourceName, $this->customers[0]->id)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanUpdateCustomer()
    {
        $name = 'ar name test';
        $mobile = 'en name test';

        $this->be(User::factory()->create())
            ->putJson('nova-api/customers/' . $this->customers[0]->id, [
                'name' => $name,
                'mobile' => $mobile,
            ])
            ->assertOk();

        $this->novaDetail($this->resourceName, $this->customers[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name', $name)
            ->assertFieldsInclude('mobile', $mobile);
    }

    /**
     * @test
     */
    public function itCanDeleteCustomer()
    {
        $this->be(User::factory()->create())
            ->deleteJson('nova-api/customers', [
                'resources' => [$this->customers[0]->id],
            ])
            ->assertOk();

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanDelete();
    }

}
