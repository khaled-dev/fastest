<?php

namespace Tests\Feature\Nova;

use Tests\TestCase;
use App\Models\Bank;
use App\Models\City;
use App\Models\User;
use App\Models\CarType;
use App\Models\Courier;
use App\Models\Territory;
use App\Models\Nationality;
use NovaTesting\NovaAssertions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CourierTest extends TestCase
{
    use NovaAssertions;

    /**
     * @var array|null
     */
    private $couriers = null;

    /**
     * @var string
     */
    private $resourceName = 'couriers';

    /**
     * setup function for job tests.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->couriers = Courier::factory(5)->create();
    }

    /**
     * @test
     */
    public function itCanNotListCouriers()
    {
        $this->get('/nova/couriers/')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function itCanListCouriers()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertFieldsInclude('name', $this->couriers[0]->name)
            ->assertFieldsInclude('mobile', $this->couriers[0]->mobile);
    }

    /**
     * @test
     */
    public function itCanShowCourier()
    {
        $this->be(User::factory()->create());

        $this->novaDetail($this->resourceName, $this->couriers[0]->id)
            ->assertOk()
            ->assertFieldsInclude('name', $this->couriers[0]->name)
            ->assertFieldsInclude('mobile', $this->couriers[0]->mobile);
    }

    /**
     * @test
     */
    public function itCanNotOpenCouriersCreateForm()
    {
        $this->be(User::factory()->create());
        $this->novaCreate($this->resourceName)
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function itCanOpenCouriersEditForm()
    {
        $this->be(User::factory()->create());
        $this->novaEdit($this->resourceName, $this->couriers[0]->id)
            ->assertOk();
    }

    /**
     * @test
     */
    public function itCanUpdateCourier()
    {
        Storage::fake('media');

        $name = 'new name';
        $territory = Territory::factory()->create();
        $city = City::factory()->create(['territory_id' => $territory->id]);
        $nationality = Nationality::factory()->create();
        $bank = Bank::factory()->create();
        $carType = CarType::factory()->create();
        $gender = 'male';
        $car_number = '123213';
        $national_number = '123123';
        $iban = '123123';

        $this->be(User::factory()->create())
            ->putJson('nova-api/couriers/' . $this->couriers[0]->id, [
                'name'            => $name,
                'territory'       => $territory->id,
                'city'            => $city->id,
                'nationality'     => $nationality->id,
                'bank'            => $bank->id,
                'gender'          => $gender,
                'car_number'      => $car_number,
                'national_number' => $national_number,
                'iban'            => $iban,
                'car_type'        => $carType->id,
                '__media__' => [
                    'profile_image'         => [UploadedFile::fake()->image('image.jpg')],
                    'national_card_image'   => [UploadedFile::fake()->image('image.jpg')],
                    'car_license_image'     => [UploadedFile::fake()->image('image.jpg')],
                    'driving_license_image' => [UploadedFile::fake()->image('image.jpg')],
                ]
            ])->assertOk();

        $this->couriers[0] = $this->couriers[0]->fresh();

        $this->assertEquals($this->couriers[0]->name, $name);
        $this->assertEquals($this->couriers[0]->territory->id, $territory->id);
        $this->assertEquals($this->couriers[0]->city->id, $city->id);
        $this->assertEquals($this->couriers[0]->nationality->id, $nationality->id);
        $this->assertEquals($this->couriers[0]->bank->id, $bank->id);
        $this->assertEquals($this->couriers[0]->gender, $gender);
        $this->assertEquals($this->couriers[0]->car_number, $car_number);
        $this->assertEquals($this->couriers[0]->national_number, $national_number);
        $this->assertEquals($this->couriers[0]->iban, $iban);

        $profile_image = $this->couriers[0]->getFirstMedia('profile_image');
        Storage::disk('media')->assertExists("$profile_image->id/$profile_image->file_name");

        $national_card_image = $this->couriers[0]->getFirstMedia('national_card_image');
        Storage::disk('media')->assertExists("$national_card_image->id/$national_card_image->file_name");

        $car_license_image = $this->couriers[0]->getFirstMedia('car_license_image');
        Storage::disk('media')->assertExists("$car_license_image->id/$car_license_image->file_name");

        $driving_license_image = $this->couriers[0]->getFirstMedia('driving_license_image');
        Storage::disk('media')->assertExists("$driving_license_image->id/$driving_license_image->file_name");
    }

    /**
     * @test
     */
    public function itCanNotDeleteCourier()
    {
        $this->be(User::factory()->create());

        $this->novaIndex($this->resourceName)
            ->assertOk()
            ->assertCanNotDelete();
    }

}
