<?php

namespace Tourze\UserIDIdcardBundle\Tests\Integration\Mock;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\MockObject\Generator;

class MockManagerRegistry implements ManagerRegistry
{
    private array $managers = [];
    
    public function __construct()
    {
        // 创建一个模拟的 EntityManager
        $generator = new Generator();
        $entityManager = $generator->getMock(EntityManagerInterface::class);
        
        $this->managers['default'] = $entityManager;
    }
    
    public function getDefaultConnectionName(): string
    {
        return 'default';
    }

    public function getConnection(?string $name = null): object
    {
        $generator = new Generator();
        return $generator->getMock(\Doctrine\DBAL\Connection::class);
    }

    public function getConnections(): array
    {
        return [];
    }

    public function getConnectionNames(): array
    {
        return ['default'];
    }

    public function getDefaultManagerName(): string
    {
        return 'default';
    }

    public function getManager(?string $name = null): ObjectManager
    {
        return $this->managers['default'];
    }

    public function getManagers(): array
    {
        return $this->managers;
    }

    public function resetManager(?string $name = null): ObjectManager
    {
        return $this->managers['default'];
    }

    public function getAliasNamespace(string $alias): string
    {
        return '';
    }

    public function getManagerNames(): array
    {
        return ['default'];
    }

    public function getRepository(string $persistentObject, ?string $persistentManagerName = null): ObjectRepository
    {
        $generator = new Generator();
        return $generator->getMock(ObjectRepository::class);
    }

    public function getManagerForClass(string $class): ?ObjectManager
    {
        return $this->managers['default'] ?? null;
    }
} 