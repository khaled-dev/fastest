<?php

namespace App\Http\Controllers;

use App\Services\FirebaseAuth;
use Illuminate\Http\Request;

class AuthToController extends Controller
{
    /**
     * @var FirebaseAuth
     */
    private $firebaseAuth;

    /**
     * AuthToController constructor.
     * @param FirebaseAuth $firebaseAuth
     */
    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    /**
     *
     */
    protected function generateFirebaseToken()
    {
//        var_dump($this->firebaseAuth->generateToken('1')->toString());
//        dd();
//        return $this->firebaseAuth->generateToken('1')->toString();
        return $this->firebaseAuth->verifyToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJmaXJlYmFzZS1hZG1pbnNkay1xbWdqbEB0aGUtZmFzdGVzdC0yMDE5LTEuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLCJzdWIiOiJmaXJlYmFzZS1hZG1pbnNkay1xbWdqbEB0aGUtZmFzdGVzdC0yMDE5LTEuaWFtLmdzZXJ2aWNlYWNjb3VudC5jb20iLCJhdWQiOiJodHRwczovL2lkZW50aXR5dG9vbGtpdC5nb29nbGVhcGlzLmNvbS9nb29nbGUuaWRlbnRpdHkuaWRlbnRpdHl0b29sa2l0LnYxLklkZW50aXR5VG9vbGtpdCIsInVpZCI6IjEiLCJpYXQiOjE2MTY1NDM2NDUuMTUxNzczLCJleHAiOjE2MTY1NDcyNDUuMTUxNzczfQ.m8sNDrz9tW21PCLjCLZvdQ4hyUgZKWQWquS5kAkmZ2h_2zYBxjDnewD7gXFQSAlWgAXRHS2teFsqtEJuRof5QxcBt4kYdHXFJkxgnkdVUd6Jtc3wF0KUx8acO7g9e2DhotJW0oUh1Yhsh-buxf5zHCKQbAEO0A3y5JfpbmBpYjGHu4zueh02eAbDJ65jkt9tYECLZnNhOPx8biYGnUKl_-cK5R0UsRHOmUDKoeMCfn8KVbNoonAZ8M1ABsK07tAY_xkJqv2zxVqQCeQnDetdmqoWlCtssd8hO0G-CvIQ197RoqC8bxJzZxOmt0Vz1wxJCSpyGiXdcbAIvJhKM0H14g')->toString();
    }
}
