<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\FirebaseAuth;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\LoginCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

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
     * @return \Illuminate\Http\Response
     */
    public function registerOrLogin(LoginCustomerRequest $request): \Illuminate\Http\Response
    {
        // $this->firebaseAuth->verifyToken('fbToken')

        $customer = Customer::where('mobile', $request->mobile)->first();

        if (empty($customer)) {
            $customer = Customer::create($request->all());
        }

        $accessToken = $customer->createToken('authToken')->accessToken;

        return response([
            'customer' => new CustomerResource($customer),
            'accessToken' => $accessToken,
        ]);
    }

    /**
      * Show customer resource
     *
     * @return CustomerResource[]
     */
    public function show(): array
    {
        return [
            'customer' => new CustomerResource(auth()->user())
        ];
    }

    /**
     * Update customer
     *
     * @param UpdateCustomerRequest $request
     * @return CustomerResource[]
     */
    public function update(UpdateCustomerRequest $request): array
    {
        auth()->user()->update($request->all());

        return [
            'customer' => new CustomerResource(auth()->user())
        ];
    }
}