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
     * Generate new FB token
     *
     * @param string $id
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken(string $id): Token
    {
        return $this->auth->createCustomToken($id);
    }

    /**
     * Verify FB token
     *
     * @param string $idToken
     * @return \Lcobucci\JWT\Token
     */
    public function verifyToken(string $idToken): Token
    {
        return $this->auth->verifyIdToken($idToken);
    }

    /**
     *  Parse FB token
     *
     * @param string $idToken
     * @return \Lcobucci\JWT\Token
     */
    public function parseToken(string $idToken): Token
    {
        return $this->auth->parseToken($idToken);
    }
}
