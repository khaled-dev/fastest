<?php


namespace App\Services\Contracts;


use Lcobucci\JWT\Token;
use Kreait\Firebase\Auth;

interface IAuthenticateOTP
{
    /**
     * Construct an object and set auth instance.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth);

    /**
     * Generate new FB token
     *
     * @param string $uid
     * @return \Lcobucci\JWT\Token
     */
    public function generateToken(string $uid): Token;

    /**
     * Verify FB token
     *
     * @param string $uidToken
     * @return \Lcobucci\JWT\Token
     */
    public function verifyToken(string $uidToken): Token;

    /**
     *  Parse FB token
     *
     * @param string $uidToken
     * @return \Lcobucci\JWT\Token
     */
    public function parseToken(string $uidToken): Token;
}
