<?php

namespace Tourze\UserIDIdcardBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel;
use Tourze\UserIDBundle\Service\UserIdentityService;
use Tourze\UserIDIdcardBundle\Tests\Integration\Mock\MockUserIdentityService;
use Tourze\UserIDIdcardBundle\UserIDIdcardBundle;

class IntegrationTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new UserIDIdcardBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->loadFromExtension('framework', [
            'test' => true,
            'secret' => 'test',
        ]);

        // 注册模拟的 UserIdentityService
        $mockServiceDefinition = new Definition(MockUserIdentityService::class);
        $mockServiceDefinition->setPublic(true);
        $container->setDefinition(UserIdentityService::class, $mockServiceDefinition);
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/cache/' . spl_object_hash($this);
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/logs/' . spl_object_hash($this);
    }
}
