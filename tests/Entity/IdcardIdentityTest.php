<?php

namespace Tourze\UserIDIdcardBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;

/**
 * @internal
 */
#[CoversClass(IdcardIdentity::class)]
final class IdcardIdentityTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new IdcardIdentity();
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'idcard' => ['idcard', 'test_value'],
        ];
    }

    /**
     * 测试获取ID方法
     */
    public function testGetIdWithDefaultValue(): void
    {
        $entity = new IdcardIdentity();
        $this->assertNull($entity->getId());
    }

    /**
     * 测试设置和获取身份证号
     */
    public function testSetAndGetIdcardWithValidValue(): void
    {
        $entity = new IdcardIdentity();
        $idcard = '110101199001011234';

        $entity->setIdcard($idcard);

        $this->assertSame($idcard, $entity->getIdcard());
    }

    /**
     * 测试设置和获取用户
     */
    public function testSetAndGetUserWithValidUser(): void
    {
        $entity = new IdcardIdentity();
        $user = $this->createMock(UserInterface::class);

        $entity->setUser($user);

        $this->assertSame($user, $entity->getUser());
    }

    /**
     * 测试设置和获取用户为null
     */
    public function testSetAndGetUserWithNullValue(): void
    {
        $entity = new IdcardIdentity();

        $entity->setUser(null);

        $this->assertNull($entity->getUser());
    }

    /**
     * 测试设置和获取创建人
     */
    public function testSetAndGetCreatedByWithValidValue(): void
    {
        $entity = new IdcardIdentity();
        $createdBy = 'admin';

        $entity->setCreatedBy($createdBy);

        $this->assertSame($createdBy, $entity->getCreatedBy());
    }

    /**
     * 测试设置和获取创建人为null
     */
    public function testSetAndGetCreatedByWithNullValue(): void
    {
        $entity = new IdcardIdentity();

        $entity->setCreatedBy(null);

        $this->assertNull($entity->getCreatedBy());
    }

    /**
     * 测试设置和获取更新人
     */
    public function testSetAndGetUpdatedByWithValidValue(): void
    {
        $entity = new IdcardIdentity();
        $updatedBy = 'admin';

        $entity->setUpdatedBy($updatedBy);

        $this->assertSame($updatedBy, $entity->getUpdatedBy());
    }

    /**
     * 测试设置和获取更新人为null
     */
    public function testSetAndGetUpdatedByWithNullValue(): void
    {
        $entity = new IdcardIdentity();

        $entity->setUpdatedBy(null);

        $this->assertNull($entity->getUpdatedBy());
    }

    /**
     * 测试设置和获取创建时间
     */
    public function testSetAndGetCreateTimeWithValidValue(): void
    {
        $entity = new IdcardIdentity();
        $dateTime = new \DateTimeImmutable();

        $entity->setCreateTime($dateTime);

        $this->assertSame($dateTime, $entity->getCreateTime());
    }

    /**
     * 测试设置和获取创建时间为null
     */
    public function testSetAndGetCreateTimeWithNullValue(): void
    {
        $entity = new IdcardIdentity();

        $entity->setCreateTime(null);

        $this->assertNull($entity->getCreateTime());
    }

    /**
     * 测试设置和获取更新时间
     */
    public function testSetAndGetUpdateTimeWithValidValue(): void
    {
        $entity = new IdcardIdentity();
        $dateTime = new \DateTimeImmutable();

        $entity->setUpdateTime($dateTime);

        $this->assertSame($dateTime, $entity->getUpdateTime());
    }

    /**
     * 测试设置和获取更新时间为null
     */
    public function testSetAndGetUpdateTimeWithNullValue(): void
    {
        $entity = new IdcardIdentity();

        $entity->setUpdateTime(null);

        $this->assertNull($entity->getUpdateTime());
    }

    /**
     * 测试获取身份标识值
     */
    public function testGetIdentityValueReturnsIdcard(): void
    {
        $entity = new IdcardIdentity();
        $idcard = '110101199001011234';
        $entity->setIdcard($idcard);

        $this->assertSame($idcard, $entity->getIdentityValue());
    }

    /**
     * 测试获取身份类型
     */
    public function testGetIdentityTypeReturnsConstant(): void
    {
        $entity = new IdcardIdentity();

        $this->assertSame(IdcardIdentity::IDENTITY_TYPE, $entity->getIdentityType());
        $this->assertSame('idcard', $entity->getIdentityType());
    }

    /**
     * 测试获取身份数组
     */
    public function testGetIdentityArrayWithValidData(): void
    {
        $entity = new IdcardIdentity();
        $idcard = '110101199001011234';
        $id = '123456';
        $now = new \DateTimeImmutable('2023-01-01 12:00:00');

        // 使用反射设置 id 属性
        $reflectionClass = new \ReflectionClass($entity);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, $id);

        $entity->setIdcard($idcard);
        $entity->setCreateTime($now);
        $entity->setUpdateTime($now);

        $result = $entity->getIdentityArray();
        $this->assertInstanceOf(\Traversable::class, $result);

        $items = iterator_to_array($result);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Identity::class, $items[0]);
        $this->assertSame($id, $items[0]->getId());
        $this->assertSame(IdcardIdentity::IDENTITY_TYPE, $items[0]->getIdentityType());
        $this->assertSame($idcard, $items[0]->getIdentityValue());

        $extra = $items[0]->getExtra();
        $this->assertArrayHasKey('createTime', $extra);
        $this->assertArrayHasKey('updateTime', $extra);
        $this->assertSame('2023-01-01 12:00:00', $extra['createTime']);
        $this->assertSame('2023-01-01 12:00:00', $extra['updateTime']);
    }

    /**
     * 测试获取身份数组时间为null
     */
    public function testGetIdentityArrayWithNullTimes(): void
    {
        $entity = new IdcardIdentity();
        $idcard = '110101199001011234';
        $id = '123456';

        // 使用反射设置 id 属性
        $reflectionClass = new \ReflectionClass($entity);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, $id);

        $entity->setIdcard($idcard);

        $result = $entity->getIdentityArray();
        $items = iterator_to_array($result);

        $extra = $items[0]->getExtra();
        $this->assertNull($extra['createTime']);
        $this->assertNull($extra['updateTime']);
    }

    /**
     * 测试获取账户
     */
    public function testGetAccountsReturnsEmptyArray(): void
    {
        $entity = new IdcardIdentity();

        $this->assertSame([], $entity->getAccounts());
    }
}
