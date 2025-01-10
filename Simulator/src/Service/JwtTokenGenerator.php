<?php

namespace App\Service;

use Symfony\Component\Mercure\Jwt\LcobucciFactory;

class JwtTokenGenerator
{
    private LcobucciFactory $factory;

    public function __construct(string $secret)
    {
//        dd($secret);
        $this->factory = new LcobucciFactory($secret);
    }

    /**
     * Create a token that allows publishing to $publish and subscribing to $subscribe.
     */
    public function createToken(?array $subscribe = [], ?array $publish = [], array $additionalClaims = []): string
    {
        return $this->factory->create($subscribe, $publish, $additionalClaims);
    }
}
