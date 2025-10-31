<?php

declare(strict_types=1);

namespace Tourze\UserIDIdcardBundle\Tests\Service;

use Knp\Menu\MenuFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use Tourze\UserIDIdcardBundle\Service\AdminMenu;

/**
 * AdminMenu服务测试
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    protected function onSetUp(): void
    {
        // Setup for AdminMenu tests
    }

    public function testInvokeAddsMenuItems(): void
    {
        $adminMenu = self::getContainer()->get(AdminMenu::class);
        self::assertInstanceOf(AdminMenu::class, $adminMenu);

        $factory = new MenuFactory();
        $rootItem = $factory->createItem('root');

        $adminMenu($rootItem);

        // 验证菜单结构
        $userMenu = $rootItem->getChild('用户管理');
        self::assertNotNull($userMenu);

        $identityMenu = $userMenu->getChild('身份认证');
        self::assertNotNull($identityMenu);

        self::assertNotNull($identityMenu->getChild('身份证管理'));
    }
}
