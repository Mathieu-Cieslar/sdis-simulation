<?php

namespace App\Service;


use Symfony\Component\Mercure\Jwt\TokenProviderInterface;

final class JwtTokenProvider implements TokenProviderInterface
{
    private JwtTokenGenerator $jwtTokenGenerator;

    public function __construct(JwtTokenGenerator $jwtTokenGenerator)
    {
        $this->jwtTokenGenerator = $jwtTokenGenerator;
    }

    public function getJwt(): string
    {
//        dd( $this->jwtTokenGenerator->createToken(['*'], ['*']));
        return $this->jwtTokenGenerator->createToken(['*'], ['*']);
    }
}
