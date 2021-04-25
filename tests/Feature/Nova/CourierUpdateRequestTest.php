<?php

namespace Tests\Feature\Nova;

use Tests\TestCase;
use App\Models\User;
use NovaTesting\NovaAssertions;
use Illuminate\Support\Collection;
use App\Models\CourierUpdateRequest;
use Laravel\Nova\Fields\ActionFields;
use App\Nova\Actions\ApproveCourierUpdate;

class CourierUpdateRequestTest extends TestCase
{
    use NovaAssertions;

    /**
     * @var array|null
     */
    private $courierUpdateRequests = null;

    /**
     * @var string
     */
    private $resourceName = 'courier-update-requests';

    /**
     * setup function for job tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->courierUpdateRequests = CourierUpdateRequest::factory(5)->create();
    }

    /**
     * @test
     */
    public function itCanNotListCourierUpdateRequests()
    {
        $this->get('/nova/courierUpdateRequests/')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function itCanListCourierUpdateRequests()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('courier')
            ->assertFieldsInclude('name', $this->courierUpdateRequests[0]->name)
            ->assertFieldsInclude('gender', $this->courierUpdateRequests[0]->gender)
            ->assertFieldsInclude('city', $this->courierUpdateRequests[0]->city->name_ar);
    }

    /**
     * @test
     */
    public function itCanShowCourierUpdateRequest()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->courierUpdateRequests[0]->id)
            ->assertOk()
            ->assertFieldsInclude('courier')
            ->assertFieldsInclude('name', $this->courierUpdateRequests[0]->name)
            ->assertFieldsInclude('gender', $this->courierUpdateRequests[0]->gender)
            ->assertFieldsInclude('bank', $this->courierUpdateRequests[0]->bank->name_ar)
            ->assertFieldsInclude('city', $this->courierUpdateRequests[0]->city->name_ar);
    }


    /**
     * @test
     */
    public function itHasActionCourierUpdateRequest()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->courierUpdateRequests[0]->id)
            ->assertOk()
            ->assertActionsInclude(ApproveCourierUpdate::class);
    }

    /**
     * @test
     */
    public function itAcceptsCourierUpdateRequest()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->courierUpdateRequests[0]->id)
            ->assertOk();

        (new ApproveCourierUpdate())
            ->handle(
                new ActionFields(new Collection, new Collection),
                collect([$this->courierUpdateRequests[0]])
            );

        $this->novaDetail($this->resourceName, $this->courierUpdateRequests[0]->id)
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function itCanNotOpenCourierUpdateRequestsCreateForm()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotCreate();
    }

    /**
     * @test
     */
    public function itCanNotOpenCourierUpdateRequestsEditForm()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotUpdate();
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
