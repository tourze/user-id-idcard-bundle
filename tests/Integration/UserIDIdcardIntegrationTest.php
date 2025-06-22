<?php

namespace Tourze\UserIDIdcardBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserIDIdcardIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return IntegrationTestKernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel(['debug' => false]);
    }

    /**
     * 测试服务类是否成功注册在容器中
     */
    public function test_serviceWiring_servicesAreWiredCorrectly()
    {
        $container = self::getContainer();

        // 创建一个具有有限功能的模拟容器
        $serviceIds = [
            'Tourze\UserIDIdcardBundle\Service\UserIdentityIdcardService',
        ];

        foreach ($serviceIds as $serviceId) {
            $this->assertTrue(
                $container->has($serviceId),
                sprintf('服务 "%s" 应该在容器中注册', $serviceId)
            );
        }
    }
}
