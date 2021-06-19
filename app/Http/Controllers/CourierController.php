<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourierRequest;
use App\Models\Courier;
use App\Rules\Mobile;
use App\Services\Exceptions\InvalidFirebaseRegistrationTokenException;
use App\Services\Logic\NotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CourierResource;
use App\Http\Requests\LoginCourierRequest;
use App\Http\Requests\CourierResetPassword;
use App\Services\Contracts\ICloudMessaging;
use App\Http\Requests\UpdateCourierRequest;
use App\Services\Contracts\IAuthenticateOTP;
use App\Http\Requests\UpdateCourierMobileRequest;
use App\Http\Requests\UpdateImagesCourierRequest;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class CourierController extends Controller
{
    /**
     * Instance of FirebaseAuth service.
     *
     * @var IAuthenticateOTP
     */
    private $firebaseAuth;

    /**
     * Instance of FirebaseCloudMessaging service.
     *
     * @var ICloudMessaging
     */
    private $cloudMessaging;

    /**
     * CustomerController constructor.
     *
     * @param IAuthenticateOTP $firebaseAuth
     * @param ICloudMessaging $cloudMessaging
     */
    public function __construct(IAuthenticateOTP $firebaseAuth, ICloudMessaging $cloudMessaging)
    {
        $this->firebaseAuth = $firebaseAuth;
        $this->cloudMessaging = $cloudMessaging;
    }

    /**
     * Register a new courier
     *
     * @param StoreCourierRequest $request
     * @return Response
     * @throws FirebaseException
     * @throws InvalidFirebaseRegistrationTokenException
     * @throws MessagingException
     */
    public function store(StoreCourierRequest $request): Response
    {
        $this->firebaseAuth->verifyToken($request->fb_token);

        $this->cloudMessaging->validateRegistrationToken($request->fb_registration_token);

        $courier = Courier::forMobile($request->mobile)
            ->notActive()
            ->first();

        if (empty($courier)) {
            $request->validate([
                'mobile' => ['required', 'max:225', 'unique:couriers', new Mobile],
            ]);

            $courier = new Courier();
            $courier->mobile = $request->mobile;
            $courier->save();
        }

        NotificationService::saveRegistrationToken($courier, $request->fb_registration_token);

        return $this->successResponse([
            'courier'     => new CourierResource($courier->refresh()),
            'accessToken' => $courier->createToken('authToken')->accessToken,
        ], [
            'update' => route('couriers.update'),
        ], 201);
    }

    /**
     * Login courier
     *
     * @param LoginCourierRequest $request
     * @return Response
     * @throws FirebaseException
     * @throws InvalidFirebaseRegistrationTokenException
     * @throws MessagingException
     */
    public function login(LoginCourierRequest $request): Response
    {
        $this->cloudMessaging->validateRegistrationToken($request->fb_registration_token);

        $courier = Courier::where('mobile', $request->mobile)->first();

        if ($courier->is_active) {
            if (! Hash::check($request->password, $courier->password)) {
                return $this->validationErrorResponse([
                    "mobile" => [
                        "Invalid mobile or password."
                    ]
                ], [
                    'resetPassword' => route('couriers.reset_password'),
                ]);
            }
        }

        NotificationService::saveRegistrationToken($courier, $request->fb_registration_token);

        return $this->successResponse([
            'courier'     => new CourierResource($courier),
            'accessToken' => $courier->createToken('authToken')->accessToken,
        ], [
            'show' => route('couriers.show'),
            'update' => route('couriers.update_request'),
            'updateMobile' => route('couriers.update_mobile'),
            'updateImages' => route('couriers.update_images'),
        ]);
    }

    /**
     * Show Courier resource
     *
     * @return Response
     */
    public function show(): Response
    {
        return $this->successResponse([
            'courier' => new CourierResource(auth()->user())
        ], [
            'update' => route('couriers.update_request'),
            'updateMobile' => route('couriers.update_mobile'),
            'updateImages' => route('couriers.update_images'),
        ]);
    }

    /**
     * Update courier
     *
     * @param UpdateCourierRequest $request
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateCourierRequest $request): Response
    {
        /** @var Courier $courier */
        $courier = auth()->user();

        $this->authorize('update', $courier);

        if ($request->new_password) {
            $courier->password = Hash::make($request->new_password);
        }

        $courier->is_active = true;

        $courier->fill($request->all())->save();

        return $this->successResponse([
            'courier' => new CourierResource( auth()->user()),
        ], [
            'show' => route('couriers.show'),
            'updateMobile' => route('couriers.update_mobile'),
            'updateImages' => route('couriers.update_images'),
        ]);
    }

    /**
     * Update courier's mobile
     *
     * @param UpdateCourierMobileRequest $request
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateMobile(UpdateCourierMobileRequest $request): Response
    {
        $this->firebaseAuth->verifyToken($request->fb_token ?? '');

        /** @var Courier $courier */
        $courier = auth()->user();

        $courier->mobile = $request->mobile;
        $courier->save();

        return $this->successResponse([
            'courier' => new CourierResource( auth()->user()),
        ],[
            'show' => route('couriers.show'),
            'update' => route('couriers.update_request'),
            'updateImages' => route('couriers.update_images'),
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

        return $this->successResponse([
            'courier' => new CourierResource( auth()->user()),
        ], [
            'show' => route('couriers.show'),
            'updateMobile' => route('couriers.update_mobile'),
            'updateImages' => route('couriers.update_images'),
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

        if ($request->exists('profile_image')) {
            $courier->addMediaFromRequest('profile_image')
                ->toMediaCollection('profile_image');
        }

        if ($request->exists('national_card_image')) {
            $courier->addMediaFromRequest('national_card_image')
                ->toMediaCollection('national_card_image');
        }

        if ($request->exists('car_license_image')) {
            $courier->addMediaFromRequest('car_license_image')
                ->toMediaCollection('car_license_image');
        }

        if ($request->exists('driving_license_image')) {
            $courier->addMediaFromRequest('driving_license_image')
                ->toMediaCollection('driving_license_image');
        }

        return $this->successResponse([
            'courier' => new CourierResource( auth()->user()),
        ], [
            'show' => route('couriers.show'),
            'updateMobile' => route('couriers.update_mobile'),
            'update' => route('couriers.update_request'),
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
         $this->firebaseAuth->verifyToken($request->fb_token ?? '');

        $courier = Courier::where('mobile', $request->mobile)->first();

        if (empty($courier)) {
            return $this->validationErrorResponse([
                "mobile" => [
                    "Invalid mobile or password."
                ]
            ]);
        }

        $courier->password = Hash::make($request->new_password);

        $courier->save();

        return $this->successResponse([
            'courier'     => new CourierResource($courier),
            'accessToken' => $courier->createToken('authToken')->accessToken,
        ], [
            'login' => route('couriers.login'),
        ]);
    }

}
