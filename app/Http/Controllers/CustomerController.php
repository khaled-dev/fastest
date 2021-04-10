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
        $this->firebaseAuth->verifyToken($request->fb_token ?? '');

        $customer = Customer::where('mobile', $request->mobile)->first();

        if (empty($customer)) {
            $customer = Customer::create($request->all());
        }

        return $this->successResponse([
            'customer' => new CustomerResource($customer),
            'accessToken' => $customer->createToken('authToken')->accessToken,
        ], [
            'show' => route('customers.show'),
            'update' => route('customers.update'),
            'updateImages' => route('customers.update_images'),
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
        ], [
            'update' => route('customers.update'),
            'updateImages' => route('customers.update_images'),
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
        ], [
            'show' => route('customers.show'),
            'updateImages' => route('customers.update_images'),
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

        if ($request->exists('profile_image')) {
            $customer->addMediaFromRequest('profile_image')
                ->toMediaCollection('profile_image');
        }

        return $this->successResponse([
            'customer' => new CustomerResource(auth()->user()),
        ], [
            'show' => route('customers.show'),
            'update' => route('customers.update'),
        ]);
    }

}
