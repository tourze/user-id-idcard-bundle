<?php

declare(strict_types=1);

namespace Tourze\UserIDIdcardBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;

/**
 * 身份证管理后台菜单提供者
 */
#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('用户管理')) {
            $item->addChild('用户管理');
        }

        $userMenu = $item->getChild('用户管理');
        if (null === $userMenu) {
            return;
        }

        // 添加身份认证子菜单
        if (null === $userMenu->getChild('身份认证')) {
            $userMenu->addChild('身份认证')
                ->setAttribute('icon', 'fas fa-id-card')
            ;
        }

        $identityMenu = $userMenu->getChild('身份认证');
        if (null === $identityMenu) {
            return;
        }

        $identityMenu->addChild('身份证管理')
            ->setUri($this->linkGenerator->getCurdListPage(IdcardIdentity::class))
            ->setAttribute('icon', 'fas fa-address-card')
        ;
    }
}
