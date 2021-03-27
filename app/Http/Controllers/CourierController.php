<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Services\FirebaseAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CourierResource;
use App\Http\Requests\LoginCourierRequest;
use App\Http\Requests\UpdateCourierRequest;
use App\Http\Requests\RegisterCourierRequest;

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
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterCourierRequest $request): \Illuminate\Http\Response
    {
        // $this->firebaseAuth->verifyToken('fbToken')

        $courier = Courier::create($request->all());

        return response([
            'courier'     => new CourierResource($courier),
            'accessToken' => $courier->createToken('authToken')->accessToken,
        ]);
    }

    /**
     * Login courier
     *
     * @param LoginCourierRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginCourierRequest $request): \Illuminate\Http\Response
    {
        // $this->firebaseAuth->verifyToken('fbToken')

        $courier = Courier::where('mobile', $request->mobile)->first();

        if (empty($courier) || ! Hash::check($request->password, $courier->password)) {
            return response([
                "message" => "The given data was invalid.",
                "errors" => [
                    "mobile" => [
                        "Invalid mobile or password."
                    ]
                ]
            ]);
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
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourierRequest $request): \Illuminate\Http\Response
    {
        auth()->user()->update($request->all());

        return response([
            'courier' => new CourierResource( auth()->user()),
        ]);
    }

}
