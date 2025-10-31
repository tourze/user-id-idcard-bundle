<?php

namespace Tourze\UserIDIdcardBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;
use Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository;

/**
 * @internal
 */
#[CoversClass(IdcardIdentityRepository::class)]
#[RunTestsInSeparateProcesses]
final class IdcardIdentityRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
        // 集成测试设置方法
    }

    protected function getRepository(): IdcardIdentityRepository
    {
        return self::getService(IdcardIdentityRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011234');

        return $entity;
    }

    public function testAssociationQueryWithUser(): void
    {
        $user = $this->createNormalUser('test@example.com', 'password');

        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011254');
        $entity->setUser($user);
        $this->getRepository()->save($entity);

        $results = $this->getRepository()->findBy(['user' => $user]);

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertSame($entity->getId(), $results[0]->getId());
    }

    public function testAssociationCountQueryWithUser(): void
    {
        $user = $this->createNormalUser('test2@example.com', 'password');

        $entity1 = new IdcardIdentity();
        $entity1->setIdcard('110101199001011255');
        $entity1->setUser($user);
        $this->getRepository()->save($entity1);

        $entity2 = new IdcardIdentity();
        $entity2->setIdcard('110101199001011256');
        $entity2->setUser($user);
        $this->getRepository()->save($entity2);

        $count = $this->getRepository()->count(['user' => $user]);

        $this->assertSame(2, $count);
    }

    public function testIsNullQueryForUser(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011257');
        $entity->setUser(null);
        $this->getRepository()->save($entity);

        $results = $this->getRepository()->findBy(['user' => null]);

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));

        // 验证找到的实体确实没有用户
        $found = false;
        foreach ($results as $result) {
            $this->assertInstanceOf(IdcardIdentity::class, $result);
            if ($result->getId() === $entity->getId()) {
                $this->assertNull($result->getUser());
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Should find the entity with null user');
    }

    public function testQueryBuilderAssociationQuery(): void
    {
        $user = $this->createNormalUser('test3@example.com', 'password');

        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011259');
        $entity->setUser($user);
        $this->getRepository()->save($entity);

        $qb = $this->getRepository()->createQueryBuilder('i');
        $results = $qb->where('i.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertSame($entity->getId(), $results[0]->getId());
    }

    public function testConstructorSetsEntityClass(): void
    {
        $this->assertInstanceOf(IdcardIdentityRepository::class, $this->getRepository());
    }

    public function testInheritanceExtendsServiceEntityRepository(): void
    {
        $this->assertInstanceOf(ServiceEntityRepository::class, $this->getRepository());
    }

    public function testIsNullQueryForCreatedBy(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011260');
        $entity->setCreatedBy(null);
        $this->getRepository()->save($entity);

        $results = $this->getRepository()->findBy(['createdBy' => null]);

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
    }

    public function testIsNullQueryForUpdatedBy(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011262');
        $entity->setUpdatedBy(null);
        $this->getRepository()->save($entity);

        $results = $this->getRepository()->findBy(['updatedBy' => null]);

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
    }

    public function testIsNullQueryForCreateTime(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011264');

        // 直接使用 EntityManager 绕过自动时间戳设置
        $em = self::getEntityManager();
        $em->persist($entity);
        $em->flush();

        // 强制设置为 null（在保存后）
        $em->createQuery('UPDATE ' . IdcardIdentity::class . ' e SET e.createTime = NULL WHERE e.id = :id')
            ->setParameter('id', $entity->getId())
            ->execute()
        ;
        $em->clear();

        $results = $this->getRepository()->findBy(['createTime' => null]);

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
    }

    public function testIsNullQueryForUpdateTime(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011266');

        // 直接使用 EntityManager 绕过自动时间戳设置
        $em = self::getEntityManager();
        $em->persist($entity);
        $em->flush();

        // 强制设置为 null（在保存后）
        $em->createQuery('UPDATE ' . IdcardIdentity::class . ' e SET e.updateTime = NULL WHERE e.id = :id')
            ->setParameter('id', $entity->getId())
            ->execute()
        ;
        $em->clear();

        $results = $this->getRepository()->findBy(['updateTime' => null]);

        $this->assertIsArray($results);
        $this->assertGreaterThanOrEqual(1, count($results));
    }

    public function testStringFieldQueryWithCreatedBy(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011268');
        $entity->setCreatedBy('test_user_1');
        $this->getRepository()->save($entity);

        $results = $this->getRepository()->findBy(['createdBy' => 'test_user_1']);

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertSame($entity->getId(), $results[0]->getId());
    }

    public function testStringFieldCountQueryWithCreatedBy(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011269');
        $entity->setCreatedBy('test_user_2');
        $this->getRepository()->save($entity);

        $count = $this->getRepository()->count(['createdBy' => 'test_user_2']);

        $this->assertSame(1, $count);
    }

    public function testStringFieldQueryWithUpdatedBy(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011270');
        $entity->setUpdatedBy('test_user_3');
        $this->getRepository()->save($entity);

        $results = $this->getRepository()->findBy(['updatedBy' => 'test_user_3']);

        $this->assertIsArray($results);
        $this->assertCount(1, $results);
        $this->assertSame($entity->getId(), $results[0]->getId());
    }

    public function testStringFieldCountQueryWithUpdatedBy(): void
    {
        $entity = new IdcardIdentity();
        $entity->setIdcard('110101199001011271');
        $entity->setUpdatedBy('test_user_4');
        $this->getRepository()->save($entity);

        $count = $this->getRepository()->count(['updatedBy' => 'test_user_4']);

        $this->assertSame(1, $count);
    }
}
