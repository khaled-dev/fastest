<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bank;
use App\Models\City;
use App\Models\Courier;
use App\Models\Territory;
use App\Models\Nationality;
use Tests\Json\JsonResponse;
use Illuminate\Http\UploadedFile;
use Tests\Json\CourierJsonResponse;
use Illuminate\Support\Facades\Hash;
use Tests\Mocks\AuthenticateOTPMocker;
use Illuminate\Support\Facades\Storage;

class CourierTest extends TestCase
{
    use AuthenticateOTPMocker, CourierJsonResponse, JsonResponse;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install');
        Courier::factory( 5)->create();
    }

    /**
     * @test
     */
    public function itStoreCourier()
    {
        $this->mockAuthenticateOTP();

        $this->postJson(route('couriers.store'), [
            'mobile' => '4444433749e6',
            'fb_token' => '000000',
        ])
        ->assertStatus(201)
        ->assertJsonStructure($this->storeJsonResponse());
    }

    /**
     * @test
     */
    public function itCanNotLoginCourier()
    {
        $this->postJson(route('couriers.login'), [
            'mobile' => '123456',
            'password' => '123456',
        ])
        ->assertStatus(422)
        ->assertJson(
            $this->validationErrorJsonResponse(['mobile' => []])
        );
    }

    /**
     * @test
     */
    public function itLoginCourier()
    {
        $courierMobile = '123123123';

        // create couriers
        Courier::factory()->create([
            'mobile' => $courierMobile,
        ]);

        $this->postJson(route('couriers.login'), [
            'mobile' => $courierMobile,
            'password' => 'password',
        ])
        ->assertOk()
        ->assertJsonStructure($this->loginJsonResponse());
    }

    /**
     * @test
     */
    public function itDoesNotShowCourier()
    {
        $this->getJson(
            route('couriers.show', 1)
        )
        ->assertStatus(401)
        ->assertJson($this->unauthenticatedJsonResponse());
    }

    /**
     * @test
     */
    public function itShowsCourier()
    {
        $courier = Courier::factory()->create();

        $this->actingAs($courier, 'couriers')
            ->getJson(route('couriers.show'))
            ->assertOk()
            ->assertJsonStructure($this->showJsonResponse());
    }

    /**
     * @test
     */
    public function itUpdatesCourier()
    {
        $courier = Courier::factory()->create();

        $name = 'new name';
        $territory = Territory::factory()->create();
        $city = City::factory()->create(['territory_id' => $territory->id]);
        $nationality = Nationality::factory()->create();
        $bank = Bank::factory()->create();
        $gender = 'male';
        $car_number = '123213';
        $national_number = '123123';
        $iban = '123123';

        $this->actingAs($courier, 'couriers')
            ->putJson(
                route('couriers.update'),
                [
                    'name'            => $name,
                    'territory_id'    => $territory->id,
                    'city_id'         => $city->id,
                    'nationality_id'  => $nationality->id,
                    'bank_id'         => $bank->id,
                    'gender'          => $gender,
                    'car_number'      => $car_number,
                    'national_number' => $national_number,
                    'iban'            => $iban,
                ]
            )
            ->assertOk()
            ->assertJsonStructure($this->updateJsonResponse());

        $this->assertEquals($courier->name, $name);
        $this->assertEquals($courier->territory->id, $territory->id);
        $this->assertEquals($courier->city->id, $city->id);
        $this->assertEquals($courier->nationality->id, $nationality->id);
        $this->assertEquals($courier->bank->id, $bank->id);
        $this->assertEquals($courier->gender, $gender);
        $this->assertEquals($courier->car_number, $car_number);
        $this->assertEquals($courier->national_number, $national_number);
        $this->assertEquals($courier->iban, $iban);
    }

    /**
     * @test
     */
    public function itStoresCourierUpdateRequest()
    {
        $courier = Courier::factory()->create();

        $name = 'new name';
        $territory = Territory::factory()->create();
        $city = City::factory()->create([
            'territory_id' => $territory->id,
        ]);
        $nationality = Nationality::factory()->create();
        $bank = Bank::factory()->create();
        $gender = 'female';
        $car_number = '123213';
        $national_number = '123123';
        $iban = '123123';

        $this->actingAs($courier, 'couriers')
            ->postJson(
                route('couriers.update_request'),
                [
                    'name'            => $name,
                    'territory_id'    => $territory->id,
                    'city_id'         => $city->id,
                    'nationality_id'  => $nationality->id,
                    'bank_id'         => $bank->id,
                    'gender'          => $gender,
                    'car_number'      => $car_number,
                    'national_number' => $national_number,
                    'iban'            => $iban,
                ]
            )
            ->assertOk()
            ->assertJsonStructure($this->updateJsonResponse());

        $this->assertNotEquals($courier->name, $name);
        $this->assertNull($courier->territory);
        $this->assertNull($courier->city);
        $this->assertNull($courier->nationality);
        $this->assertNull($courier->bank);
        $this->assertNotEquals($courier->gender, $gender);
        $this->assertNotEquals($courier->car_number, $car_number);
        $this->assertNotEquals($courier->national_number, $national_number);
        $this->assertNotEquals($courier->iban, $iban);

        $this->assertEquals($courier->courierUpdateRequest->name, $name);
        $this->assertEquals($courier->courierUpdateRequest->territory->id, $territory->id);
        $this->assertEquals($courier->courierUpdateRequest->city->id, $city->id);
        $this->assertEquals($courier->courierUpdateRequest->nationality->id, $nationality->id);
        $this->assertEquals($courier->courierUpdateRequest->bank->id, $bank->id);
        $this->assertEquals($courier->courierUpdateRequest->gender, $gender);
        $this->assertEquals($courier->courierUpdateRequest->car_number, $car_number);
        $this->assertEquals($courier->courierUpdateRequest->national_number, $national_number);
        $this->assertEquals($courier->courierUpdateRequest->iban, $iban);
    }

    /**
     * @test
     */
    public function itUpdatesCourierMobile()
    {
        $this->mockAuthenticateOTP();

        $courier = Courier::factory()->create();

        $mobile = '123123123';

        $this->actingAs($courier, 'couriers')
            ->putJson(
                route('couriers.update_mobile'),
                [
                    'mobile'   => $mobile,
                    'fb_token' => '123123123',
                ]
            )
            ->assertOk()
            ->assertJsonStructure($this->updateMobileJsonResponse());

        $this->assertEquals($courier->mobile, $mobile);
    }

    /**
     * @test
     */
    public function itResetsCourierPassword()
    {
        $this->mockAuthenticateOTP();

        $mobile = '123123123';
        $password = '123123123';

        $courier = Courier::factory([
            'mobile' => $mobile,
        ])->create();

        $this->actingAs($courier, 'couriers')
            ->putJson(
                route('couriers.reset_password'),
                [
                    'mobile' => $mobile,
                    'fb_token' => 'fb token',
                    'new_password' => $password,
                    'new_password_confirmation' => $password,
                ]
            )
            ->assertOk()
            ->assertJsonStructure($this->resetPasswordJsonResponse());

        $courier = Courier::all()->where('mobile', $mobile)->first();
        $this->assertTrue(Hash::check($password, $courier->password));
    }

    /**
     * @test
     */
    public function itDoesNotUpdatesCourierImages()
    {
        $courier = Courier::factory()->create();

        $request = $this->actingAs($courier, 'couriers');

        $request->postJson(route('couriers.update_images'), ['profile_image' => 'some text'])
                ->assertJson($this->validationErrorJsonResponse(['profile_image' => []]))
                ->assertStatus(422);

        $request->postJson(route('couriers.update_images'), ['national_card_image' => 'some text'])
                ->assertJson($this->validationErrorJsonResponse(['national_card_image' => []]))
                ->assertStatus(422);

        $request->postJson(route('couriers.update_images'), ['car_license_image' => 'some text'])
                ->assertJson($this->validationErrorJsonResponse(['car_license_image' => []]))
                ->assertStatus(422);

        $request->postJson(route('couriers.update_images'), ['driving_license_image' => 'some text'])
                ->assertJson($this->validationErrorJsonResponse(['driving_license_image' => []]))
                ->assertStatus(422);
    }

    /**
     * @test
     */
    public function itUpdatesCourierImages()
    {
        Storage::fake('media');
        $courier = Courier::factory()->create();

        $this->actingAs($courier, 'couriers')
            ->postJson(
                route('couriers.update_images'),
                [
                    'profile_image' => UploadedFile::fake()->image('image.jpg'),
                    'national_card_image' => UploadedFile::fake()->image('image.jpg'),
                    'car_license_image' => UploadedFile::fake()->image('image.jpg'),
                    'driving_license_image' => UploadedFile::fake()->image('image.jpg'),
                ]
            )
            ->assertOk()
            ->assertJsonStructure($this->updateImagesJsonResponse());

        $profile_image = $courier->getFirstMedia('profile_image');
        Storage::disk('media')->assertExists("$profile_image->id/$profile_image->file_name");

        $national_card_image = $courier->getFirstMedia('national_card_image');
        Storage::disk('media')->assertExists("$national_card_image->id/$national_card_image->file_name");

        $car_license_image = $courier->getFirstMedia('car_license_image');
        Storage::disk('media')->assertExists("$car_license_image->id/$car_license_image->file_name");

        $driving_license_image = $courier->getFirstMedia('driving_license_image');
        Storage::disk('media')->assertExists("$driving_license_image->id/$driving_license_image->file_name");
    }
}
