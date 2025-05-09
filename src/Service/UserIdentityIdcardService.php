<?php

namespace Tourze\UserIDIdcardBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Service\UserIdentityService;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;
use Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository;

#[AsDecorator(decorates: UserIdentityService::class)]
class UserIdentityIdcardService implements UserIdentityService
{
    public function __construct(
        private readonly IdcardIdentityRepository $idcardIdentityRepository,
        #[AutowireDecorated] private readonly UserIdentityService $inner,
    ) {
    }

    public function findByType(string $type, string $value): ?IdentityInterface
    {
        // 身份证
        if (IdcardIdentity::IDENTITY_TYPE === $type) {
            $result = $this->idcardIdentityRepository?->findOneBy(['idcard' => $value]);
            if ($result) {
                return $result;
            }
        }

        return $this->inner->findByType($type, $value);
    }

    public function findByUser(UserInterface $user): iterable
    {
        foreach ($this->idcardIdentityRepository->findBy(['user' => $user]) as $item) {
            yield $item;
        }
        foreach ($this->inner->findByUser($user) as $item) {
            yield $item;
        }
    }
}
