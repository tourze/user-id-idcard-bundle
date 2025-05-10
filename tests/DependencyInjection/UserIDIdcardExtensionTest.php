<?php

namespace Tourze\UserIDIdcardBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserIDIdcardBundle\DependencyInjection\UserIDIdcardExtension;

class UserIDIdcardExtensionTest extends TestCase
{
    /**
     * 测试加载扩展
     */
    public function test_load_registersServices()
    {
        $extension = new UserIDIdcardExtension();
        $container = new ContainerBuilder();
        
        $extension->load([], $container);
        
        // 检查容器中是否包含相关服务定义
        $this->assertTrue($container->hasDefinition('Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository') || 
                          $container->hasAlias('Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository'));
        $this->assertTrue($container->hasDefinition('Tourze\UserIDIdcardBundle\Service\UserIdentityIdcardService') || 
                          $container->hasAlias('Tourze\UserIDIdcardBundle\Service\UserIdentityIdcardService'));
    }
    
    /**
     * 测试服务自动装配和配置
     */
    public function test_load_configuresDefaults()
    {
        $extension = new UserIDIdcardExtension();
        $container = new ContainerBuilder();
        
        $extension->load([], $container);
        
        // 检查是否有 Repository 和 Service 服务定义
        $repoDefinitions = array_filter(
            $container->getDefinitions(),
            fn($id) => str_starts_with($id, 'Tourze\UserIDIdcardBundle\Repository\\'),
            ARRAY_FILTER_USE_KEY
        );
        
        $serviceDefinitions = array_filter(
            $container->getDefinitions(),
            fn($id) => str_starts_with($id, 'Tourze\UserIDIdcardBundle\Service\\'),
            ARRAY_FILTER_USE_KEY
        );
        
        $this->assertNotEmpty($repoDefinitions, '应该至少有一个仓库服务定义');
        $this->assertNotEmpty($serviceDefinitions, '应该至少有一个服务定义');
        
        // 检查一个服务定义是否设置了自动装配
        $repoDefinition = reset($repoDefinitions);
        $this->assertTrue($repoDefinition->isAutowired(), '仓库服务应该设置了自动装配');
        
        // 检查一个服务定义是否设置了自动配置
        $serviceDefinition = reset($serviceDefinitions);
        $this->assertTrue($serviceDefinition->isAutoconfigured(), '服务应该设置了自动配置');
    }
} 