<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Customer;
use Tests\Json\JsonResponse;
use Illuminate\Http\UploadedFile;
use Tests\Json\CustomerJsonResponse;
use Tests\Mocks\AuthenticateOTPMocker;
use Illuminate\Support\Facades\Storage;

class CustomerTest extends TestCase
{
    use AuthenticateOTPMocker, CustomerJsonResponse, JsonResponse;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install');
        Customer::factory( 5)->create();
    }

    /**
     * @test
     */
    public function itLoginOrStoreCustomer()
    {
        $this->mockAuthenticateOTP();

        $this->postJson(route('customers.login'), [
                'mobile' => '4444433749e6',
                'fb_token' => '000000',
        ])
        ->assertOk()
        ->assertJsonStructure($this->loginOrStoreJsonResponse());
    }


    /**
     * @test
     */
    public function itDoesNotShowCustomer()
    {
        $this->getJson(
            route('customers.show', 1)
        )->assertStatus(401)
         ->assertJson($this->unauthenticatedJsonResponse());
    }

    /**
     * @test
     */
    public function itShowsCustomer()
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customers')
            ->getJson(route('customers.show'))
            ->assertOk()
            ->assertJsonStructure($this->showJsonResponse());
    }

    /**
     * @test
     */
    public function itUpdatesCustomer()
    {
        $customer = Customer::factory()->create();

        $newNameValue = 'new name';

        $this->actingAs($customer, 'customers')
            ->putJson(
                route('customers.update'),
                [
                    'name' => $newNameValue,
                    'mobile' => $customer->mobile,
                ]
            )
            ->assertOk()
            ->assertJsonStructure($this->updateJsonResponse());


        $this->assertEquals($customer->name, $newNameValue);
    }

    /**
     * @test
     */
    public function itDoesNotUpdatesCustomerImages()
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customers')
            ->postJson(route('customers.update_images'), [
                'profile_image' => 'some text',
            ])->assertStatus(422)
            ->assertJson(
                $this->validationErrorJsonResponse(['profile_image' => []])
            );
    }

    /**
     * @test
     */
    public function itUpdatesCustomerImages()
    {
        Storage::fake('media');
        $image = UploadedFile::fake()->image('avatar.jpg');

        $customer = Customer::factory()->create();

        $this->actingAs($customer, 'customers')
            ->postJson(
                route('customers.update_images'),
                ['profile_image' => $image]
            )
            ->assertOk()
            ->assertJsonStructure($this->updateImagesJsonResponse());

        $profile_image = $customer->getFirstMedia('profile_image');
        Storage::disk('media')->assertExists("$profile_image->id/$profile_image->file_name");
    }
}
