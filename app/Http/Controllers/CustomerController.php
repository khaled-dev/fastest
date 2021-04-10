<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Response;
use App\Http\Resources\CustomerResource;
use App\Services\FirebaseAuth\FirebaseAuth;
use App\Http\Requests\LoginCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateImagesCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Instance of FirebaseAuth service.
     *
     * @var FirebaseAuth
     */
    private $firebaseAuth;

    /**
     * CustomerController constructor.
     *
     * @param FirebaseAuth $firebaseAuth
     */
    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    /**
     * Register a new customer or login with an old one
     *
     * @param LoginCustomerRequest $request
     * @return Response
     */
    public function registerOrLogin(LoginCustomerRequest $request): Response
    {
        $this->firebaseAuth->verifyToken($request->fbToken ?? '');

        $customer = Customer::where('mobile', $request->mobile)->first();

        if (empty($customer)) {
            $customer = Customer::create($request->all());
        }

        return $this->successResponse([
            'customer' => new CustomerResource($customer),
            'accessToken' => $customer->createToken('authToken')->accessToken,
        ]);
    }

    /**
      * Show customer resource
     *
     * @return Response
     */
    public function show(): Response
    {
        return $this->successResponse([
            'customer' => new CustomerResource(auth()->user())
        ]);
    }

    /**
     * Update customer
     *
     * @param UpdateCustomerRequest $request
     * @return Response
     */
    public function update(UpdateCustomerRequest $request): Response
    {
        auth()->user()->update($request->all());

        return $this->successResponse([
            'customer' => new CustomerResource(auth()->user())
        ]);
    }

    /**
     * Update customer's images
     *
     * @param UpdateImagesCustomerRequest $request
     * @return Response
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function updateImages(UpdateImagesCustomerRequest $request): Response
    {
        /** @var Customer $customer */
        $customer = auth()->user();

        if ($request->exists('profile_picture')) {
            $customer->addMediaFromRequest('profile_picture')
                ->toMediaCollection('profile_picture');
        }

        return $this->successResponse([
            'customer' => new CustomerResource(auth()->user()),
        ]);
    }

}
