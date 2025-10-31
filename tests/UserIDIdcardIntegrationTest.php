<?php

namespace Tourze\UserIDIdcardBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository;
use Tourze\UserIDIdcardBundle\UserIDIdcardBundle;

/**
 * @internal
 */
#[CoversClass(UserIDIdcardBundle::class)]
#[RunTestsInSeparateProcesses]
final class UserIDIdcardIntegrationTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 集成测试设置方法
    }

    /**
     * 测试 Bundle 服务注册
     */
    public function testBundleServicesAreRegistered(): void
    {
        // 测试服务容器中是否包含该 Bundle 的服务
        $this->assertTrue(self::getContainer()->has(IdcardIdentityRepository::class));

        // 在测试环境中，装饰器服务可能不注册（因为被装饰的服务不存在）
        // 这是正常的行为
    }

    /**
     * 测试 Bundle 配置加载
     */
    public function testBundleConfigurationLoads(): void
    {
        // 验证 Bundle 的配置能够正确加载
        // 使用类名检查服务是否存在
        $this->assertTrue(self::getContainer()->has(IdcardIdentityRepository::class));

        // 检查Repository服务能否正常获取
        $repository = self::getService(IdcardIdentityRepository::class);
        $this->assertInstanceOf(IdcardIdentityRepository::class, $repository);
    }
}
