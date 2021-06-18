<?php


namespace App\Services\FirebaseAuth;

use Lcobucci\JWT\Token;
use Kreait\Firebase\Auth;
use App\Services\Contracts\IAuthenticateOTP;

class FirebaseAuth implements IAuthenticateOTP
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * Construct an object and set auth instance.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Generate new FB token
     *
     * @param string $uid
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken(string $uid): Token
    {
        return $this->auth->createCustomToken($uid);
    }

    /**
     * Verify FB token
     *
     * @param string $uidToken
     * @return \Lcobucci\JWT\Token
     */
    public function verifyToken(string $uidToken): Token
    {
        return $this->auth->verifyIdToken($uidToken);
    }

    /**
     *  Parse FB token
     *
     * @param string $uidToken
     * @return \Lcobucci\JWT\Token
     */
    public function parseToken(string $uidToken): Token
    {
        return $this->auth->parseToken($uidToken);
    }
}
