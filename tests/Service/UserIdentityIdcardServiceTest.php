<?php

namespace Tourze\UserIDIdcardBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Service\UserIdentityService;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;
use Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository;
use Tourze\UserIDIdcardBundle\Service\UserIdentityIdcardService;

class UserIdentityIdcardServiceTest extends TestCase
{
    private UserIdentityIdcardService $service;
    private IdcardIdentityRepository $repository;
    private UserIdentityService $innerService;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(IdcardIdentityRepository::class);
        $this->innerService = $this->createMock(UserIdentityService::class);
        $this->service = new UserIdentityIdcardService($this->repository, $this->innerService);
    }

    /**
     * 测试构造函数
     */
    public function test_constructor_setsRepositoryAndInnerService()
    {
        $repository = $this->createMock(IdcardIdentityRepository::class);
        $innerService = $this->createMock(UserIdentityService::class);
        
        $service = new UserIdentityIdcardService($repository, $innerService);
        
        // 使用反射检查私有属性
        $reflectionClass = new \ReflectionClass($service);
        
        $repositoryProperty = $reflectionClass->getProperty('idcardIdentityRepository');
        $repositoryProperty->setAccessible(true);
        
        $innerServiceProperty = $reflectionClass->getProperty('inner');
        $innerServiceProperty->setAccessible(true);
        
        $this->assertSame($repository, $repositoryProperty->getValue($service));
        $this->assertSame($innerService, $innerServiceProperty->getValue($service));
    }

    /**
     * 测试 findByType 方法当类型为身份证且找到记录时
     */
    public function test_findByType_whenTypeIsIdcardAndIdentityFound()
    {
        $type = IdcardIdentity::IDENTITY_TYPE;
        $value = '110101199001011234';
        $identity = $this->createMock(IdcardIdentity::class);
        
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['idcard' => $value])
            ->willReturn($identity);
            
        // 内部服务不应该被调用
        $this->innerService->expects($this->never())
            ->method('findByType');
            
        $result = $this->service->findByType($type, $value);
        
        $this->assertSame($identity, $result);
    }
    
    /**
     * 测试 findByType 方法当类型为身份证但未找到记录时
     */
    public function test_findByType_whenTypeIsIdcardButIdentityNotFound()
    {
        $type = IdcardIdentity::IDENTITY_TYPE;
        $value = '110101199001011234';
        $identity = $this->createMock(IdentityInterface::class);
        
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['idcard' => $value])
            ->willReturn(null);
            
        // 内部服务应该被调用
        $this->innerService->expects($this->once())
            ->method('findByType')
            ->with($type, $value)
            ->willReturn($identity);
            
        $result = $this->service->findByType($type, $value);
        
        $this->assertSame($identity, $result);
    }
    
    /**
     * 测试 findByType 方法当类型不是身份证时
     */
    public function test_findByType_whenTypeIsNotIdcard()
    {
        $type = 'email';
        $value = 'test@example.com';
        $identity = $this->createMock(IdentityInterface::class);
        
        // 仓库不应该被调用
        $this->repository->expects($this->never())
            ->method('findOneBy');
            
        // 内部服务应该被调用
        $this->innerService->expects($this->once())
            ->method('findByType')
            ->with($type, $value)
            ->willReturn($identity);
            
        $result = $this->service->findByType($type, $value);
        
        $this->assertSame($identity, $result);
    }
    
    /**
     * 测试 findByUser 方法
     */
    public function test_findByUser_mergesResultsFromBothSources()
    {
        $user = $this->createMock(UserInterface::class);
        $idcardIdentity1 = $this->createMock(IdcardIdentity::class);
        $idcardIdentity2 = $this->createMock(IdcardIdentity::class);
        $otherIdentity1 = $this->createMock(IdentityInterface::class);
        $otherIdentity2 = $this->createMock(IdentityInterface::class);
        
        // 仓库返回身份证身份信息
        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([$idcardIdentity1, $idcardIdentity2]);
            
        // 内部服务返回其他类型的身份信息
        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([$otherIdentity1, $otherIdentity2]);
            
        $result = $this->service->findByUser($user);
        
        // 将迭代器转换为数组
        $resultArray = iterator_to_array($result);
        
        $this->assertCount(4, $resultArray);
        $this->assertSame($idcardIdentity1, $resultArray[0]);
        $this->assertSame($idcardIdentity2, $resultArray[1]);
        $this->assertSame($otherIdentity1, $resultArray[2]);
        $this->assertSame($otherIdentity2, $resultArray[3]);
    }
    
    /**
     * 测试 findByUser 方法当用户没有身份证时
     */
    public function test_findByUser_whenUserHasNoIdcardIdentities()
    {
        $user = $this->createMock(UserInterface::class);
        $otherIdentity = $this->createMock(IdentityInterface::class);
        
        // 仓库返回空数组
        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([]);
            
        // 内部服务返回其他类型的身份信息
        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([$otherIdentity]);
            
        $result = $this->service->findByUser($user);
        
        // 将迭代器转换为数组
        $resultArray = iterator_to_array($result);
        
        $this->assertCount(1, $resultArray);
        $this->assertSame($otherIdentity, $resultArray[0]);
    }
    
    /**
     * 测试 findByUser 方法当内部服务返回空结果时
     */
    public function test_findByUser_whenInnerServiceReturnsNoResults()
    {
        $user = $this->createMock(UserInterface::class);
        $idcardIdentity = $this->createMock(IdcardIdentity::class);
        
        // 仓库返回身份证身份信息
        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([$idcardIdentity]);
            
        // 内部服务返回空数组
        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([]);
            
        $result = $this->service->findByUser($user);
        
        // 将迭代器转换为数组
        $resultArray = iterator_to_array($result);
        
        $this->assertCount(1, $resultArray);
        $this->assertSame($idcardIdentity, $resultArray[0]);
    }
} 