<?php


namespace App\Services;

use Kreait\Firebase\Auth;
use Lcobucci\JWT\Token;

class FirebaseAuth
{
    /**
     * @var Auth
     */
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param string $id
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken(string $id): Token
    {
        return $this->auth->createCustomToken($id);
    }

    /**
     * @param string $idToken
     * @return \Lcobucci\JWT\Token
     */
    public function verifyToken(string $idToken): Token
    {
        return $this->auth->verifyIdToken($idToken);
    }
}
