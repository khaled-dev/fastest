<?php


namespace Tests\Mocks;


use App\Services\contracts\IAuthenticateOTP;
use Lcobucci\JWT\Token;
use Mockery;
use Mockery\MockInterface;

trait AuthenticateOTPMocker
{

    /**
     * Mocking IAuthenticateOTP concrete class.
     *
     * @return void
     */
    public function mockAuthenticateOTP(): void
    {
        $this->instance(
            IAuthenticateOTP::class,
            Mockery::mock(IAuthenticateOTP::class, function (MockInterface $mock) {
                $mock->shouldReceive('verifyToken')->once()->andReturn(
                    Mockery::mock(Token::class)
                );
            })
        );
    }

}
