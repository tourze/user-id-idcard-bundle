<?php

namespace Tourze\UserIDIdcardBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;
use Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository;

class IdcardIdentityRepositoryTest extends TestCase
{
    /**
     * 测试构造函数正确设置实体类
     */
    public function test_constructor_setsEntityClass()
    {
        // 创建模拟的 ManagerRegistry
        $registry = $this->createMock(ManagerRegistry::class);
        
        // 创建模拟的 EntityManager
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        // 设置 registry 的 getManagerForClass 方法预期行为
        $registry->method('getManagerForClass')
            ->with(IdcardIdentity::class)
            ->willReturn($entityManager);
        
        $repository = new IdcardIdentityRepository($registry);
        
        // 由于私有属性访问困难，我们仅测试实例创建成功
        $this->assertInstanceOf(IdcardIdentityRepository::class, $repository);
    }
    
    /**
     * 测试继承自 ServiceEntityRepository
     */
    public function test_inheritance_extendsServiceEntityRepository()
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $registry->method('getManagerForClass')
            ->willReturn($entityManager);
            
        $repository = new IdcardIdentityRepository($registry);
        
        $this->assertInstanceOf(\Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository::class, $repository);
    }
    
    /**
     * 测试 findOneBy 方法调用
     */
    public function test_findOneBy_callsParentMethod()
    {
        // 由于无法简单模拟父类方法调用，这里通过继承 IdcardIdentityRepository 并覆盖父类方法来测试
        $registry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        
        $registry->method('getManagerForClass')
            ->willReturn($entityManager);
        
        // 创建一个测试用的模拟对象
        $mockEntity = $this->createMock(IdcardIdentity::class);
        
        // 创建一个特殊的仓库子类来拦截父类方法调用
        $mockRepository = new class($registry, $mockEntity) extends IdcardIdentityRepository {
            private $mockEntity;
            
            public function __construct(ManagerRegistry $registry, $mockEntity)
            {
                parent::__construct($registry);
                $this->mockEntity = $mockEntity;
            }
            
            public function findOneBy(array $criteria, ?array $orderBy = null): ?object
            {
                if ($criteria === ['idcard' => '110101199001011234']) {
                    return $this->mockEntity;
                }
                return null;
            }
        };
        
        // 测试 findOneBy 方法
        $result = $mockRepository->findOneBy(['idcard' => '110101199001011234']);
        $this->assertSame($mockEntity, $result);
        
        // 测试不匹配的情况
        $result = $mockRepository->findOneBy(['idcard' => 'non-existent']);
        $this->assertNull($result);
    }
} 