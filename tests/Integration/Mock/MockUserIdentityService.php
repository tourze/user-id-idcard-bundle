<?php

namespace Tourze\UserIDIdcardBundle\Tests\Integration\Mock;

use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Service\UserIdentityService;

class MockUserIdentityService implements UserIdentityService
{
    public function findByType(string $type, string $value): ?IdentityInterface
    {
        return null;
    }

    public function findByUser(UserInterface $user): iterable
    {
        return [];
    }
} 