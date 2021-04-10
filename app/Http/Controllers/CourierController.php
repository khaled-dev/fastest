<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Response;
use App\Services\FirebaseAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CourierResource;
use App\Http\Requests\LoginCourierRequest;
use App\Http\Requests\CourierResetPassword;
use App\Http\Requests\UpdateCourierRequest;
use App\Http\Requests\RegisterCourierRequest;
use App\Http\Requests\UpdateImagesCourierRequest;

class CourierController extends Controller
{
    /**
     * Instance of FirebaseAuth service.
     *
     * @var FirebaseAuth
     */
    private $firebaseAuth;

    /**
     * CourierController constructor.
     *
     * @param FirebaseAuth $firebaseAuth
     */
    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    /**
     * Register a new courier
     *
     * @param RegisterCourierRequest $request
     * @return Response
     */
    public function store(RegisterCourierRequest $request): Response
    {
        try {
            $this->firebaseAuth->verifyToken($request->fbToken ?? '');
        } catch (\Exception $exception) {
            return response([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'fbToken' => [
                        $exception->getMessage()
                    ],
                ],
            ], 422);
        }

        $courier = new Courier();
        $courier->mobile = $request->mobile;
        $courier->save();

        return response([
            'courier'     => new CourierResource($courier),
            'accessToken' => $courier->createToken('authToken')->accessToken,
        ]);
    }

    /**
     * Login courier
     *
     * @param LoginCourierRequest $request
     * @return Response
     */
    public function login(LoginCourierRequest $request): Response
    {

        $courier = Courier::where('mobile', $request->mobile)->first();

        if (empty($courier) || ! Hash::check($request->password, $courier->password)) {
            return response([
                "message" => "The given data was invalid.",
                "errors" => [
                    "mobile" => [
                        "Invalid mobile or password."
                    ]
                ]
            ], 401);
        }

        return response([
            'courier'     => new CourierResource($courier),
            'accessToken' => $courier->createToken('authToken')->accessToken,
        ]);
    }

    /**
     * Show Courier resource
     *
     * @return CourierResource[]
     */
    public function show(): array
    {
        return [
            'courier' => new CourierResource(auth()->user())
        ];
    }

    /**
     * Update courier
     *
     * @param UpdateCourierRequest $request
     * @return Response
     */
    public function update(UpdateCourierRequest $request): Response
    {
        /** @var Courier $courier */
        $courier = auth()->user();

        if ($request->new_password) {
            $courier->password = Hash::make($request->new_password);
        }

        $courier->is_active = 1;

        $courier->fill($request->all())->save();

        return response([
            'courier' => new CourierResource( auth()->user()),
        ]);
    }

    /**
     * Story Update request for courier
     *
     * @param UpdateCourierRequest $request
     * @return Response
     */
    public function storeUpdateRequest(UpdateCourierRequest $request): Response
    {
        /** @var Courier $courier */
        $courier = auth()->user();

        // Update password
        if ($request->new_password) {
            $courier->password = Hash::make($request->new_password);
            $courier->save();
        }

        // Store courierUpdateRequest
        $courier->courierUpdateRequest()->updateOrCreate(
            ['courier_id' => $courier->id],
            $request->except([
                'new_password',
                'new_password_confirmation',
                'mobile'
            ])
        );

        return response([
            'courier' => new CourierResource( auth()->user()),
        ]);
    }

    /**
     * Update courier's images
     *
     * @param UpdateImagesCourierRequest $request
     * @return Response
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function updateImages(UpdateImagesCourierRequest $request): Response
    {
        /** @var Courier $courier */
        $courier = auth()->user();

        if ($request->exists('profile_picture')) {
            $courier->addMediaFromRequest('profile_picture')
                ->toMediaCollection('profile_picture');
        }

        if ($request->exists('national_card_picture')) {
            $courier->addMediaFromRequest('national_card_picture')
                ->toMediaCollection('national_card_picture');
        }

        if ($request->exists('car_license_picture')) {
            $courier->addMediaFromRequest('car_license_picture')
                ->toMediaCollection('car_license_picture');
        }

        if ($request->exists('driving_license_picture')) {
            $courier->addMediaFromRequest('driving_license_picture')
                ->toMediaCollection('driving_license_picture');
        }

        return response([
            'courier' => new CourierResource( auth()->user()),
        ]);
    }

    /**
     * Reset password
     *
     * @param CourierResetPassword $request
     * @return Response
     */
    public function resetPassword(CourierResetPassword $request): Response
    {
        // $this->firebaseAuth->verifyToken('fbToken')

        $courier = Courier::where('mobile', $request->mobile)->first();

        if (empty($courier)) {
            return response([
                "message" => "The given data was invalid.",
                "errors" => [
                    "mobile" => [
                        "Invalid mobile number."
                    ]
                ]
            ]);
        }

        $courier->password = Hash::make($request->password);

        return response([
            'courier'     => new CourierResource($courier),
            'accessToken' => $courier->createToken('authToken')->accessToken,
        ]);
    }

}
