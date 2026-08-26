<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;

final readonly class ApiTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $user = $this->userRepository->findOneBy(['apiToken' => $accessToken]);

        if (!$user) {
            throw new BadCredentialsException('Invalid API token.');
        }

        // identifier used to reload the user via the provider
        return new UserBadge($user->getUserIdentifier());
    }
}
