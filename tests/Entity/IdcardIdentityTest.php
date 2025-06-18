<?php

namespace Tourze\UserIDIdcardBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;

class IdcardIdentityTest extends TestCase
{
    /**
     * 测试获取ID方法
     */
    public function test_getId_withDefaultValue()
    {
        $entity = new IdcardIdentity();
        $this->assertNull($entity->getId());
    }

    /**
     * 测试设置和获取身份证号
     */
    public function test_setAndGetIdcard_withValidValue()
    {
        $entity = new IdcardIdentity();
        $idcard = '110101199001011234';

        $result = $entity->setIdcard($idcard);
        
        $this->assertSame($entity, $result);
        $this->assertSame($idcard, $entity->getIdcard());
    }

    /**
     * 测试设置和获取用户
     */
    public function test_setAndGetUser_withValidUser()
    {
        $entity = new IdcardIdentity();
        $user = $this->createMock(UserInterface::class);

        $result = $entity->setUser($user);
        
        $this->assertSame($entity, $result);
        $this->assertSame($user, $entity->getUser());
    }

    /**
     * 测试设置和获取用户为null
     */
    public function test_setAndGetUser_withNullValue()
    {
        $entity = new IdcardIdentity();
        
        $result = $entity->setUser(null);
        
        $this->assertSame($entity, $result);
        $this->assertNull($entity->getUser());
    }

    /**
     * 测试设置和获取创建人
     */
    public function test_setAndGetCreatedBy_withValidValue()
    {
        $entity = new IdcardIdentity();
        $createdBy = 'admin';

        $result = $entity->setCreatedBy($createdBy);
        
        $this->assertSame($entity, $result);
        $this->assertSame($createdBy, $entity->getCreatedBy());
    }

    /**
     * 测试设置和获取创建人为null
     */
    public function test_setAndGetCreatedBy_withNullValue()
    {
        $entity = new IdcardIdentity();
        
        $result = $entity->setCreatedBy(null);
        
        $this->assertSame($entity, $result);
        $this->assertNull($entity->getCreatedBy());
    }

    /**
     * 测试设置和获取更新人
     */
    public function test_setAndGetUpdatedBy_withValidValue()
    {
        $entity = new IdcardIdentity();
        $updatedBy = 'admin';

        $result = $entity->setUpdatedBy($updatedBy);
        
        $this->assertSame($entity, $result);
        $this->assertSame($updatedBy, $entity->getUpdatedBy());
    }

    /**
     * 测试设置和获取更新人为null
     */
    public function test_setAndGetUpdatedBy_withNullValue()
    {
        $entity = new IdcardIdentity();
        
        $result = $entity->setUpdatedBy(null);
        
        $this->assertSame($entity, $result);
        $this->assertNull($entity->getUpdatedBy());
    }

    /**
     * 测试设置和获取创建时间
     */
    public function test_setAndGetCreateTime_withValidValue()
    {
        $entity = new IdcardIdentity();
        $dateTime = new \DateTimeImmutable();

        $entity->setCreateTime($dateTime);
        
        $this->assertSame($dateTime, $entity->getCreateTime());
    }

    /**
     * 测试设置和获取创建时间为null
     */
    public function test_setAndGetCreateTime_withNullValue()
    {
        $entity = new IdcardIdentity();
        
        $entity->setCreateTime(null);
        
        $this->assertNull($entity->getCreateTime());
    }

    /**
     * 测试设置和获取更新时间
     */
    public function test_setAndGetUpdateTime_withValidValue()
    {
        $entity = new IdcardIdentity();
        $dateTime = new \DateTimeImmutable();

        $entity->setUpdateTime($dateTime);
        
        $this->assertSame($dateTime, $entity->getUpdateTime());
    }

    /**
     * 测试设置和获取更新时间为null
     */
    public function test_setAndGetUpdateTime_withNullValue()
    {
        $entity = new IdcardIdentity();
        
        $entity->setUpdateTime(null);
        
        $this->assertNull($entity->getUpdateTime());
    }

    /**
     * 测试获取身份标识值
     */
    public function test_getIdentityValue_returnsIdcard()
    {
        $entity = new IdcardIdentity();
        $idcard = '110101199001011234';
        $entity->setIdcard($idcard);
        
        $this->assertSame($idcard, $entity->getIdentityValue());
    }

    /**
     * 测试获取身份类型
     */
    public function test_getIdentityType_returnsConstant()
    {
        $entity = new IdcardIdentity();
        
        $this->assertSame(IdcardIdentity::IDENTITY_TYPE, $entity->getIdentityType());
        $this->assertSame('idcard', $entity->getIdentityType());
    }

    /**
     * 测试获取身份数组
     */
    public function test_getIdentityArray_withValidData()
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
    public function test_getIdentityArray_withNullTimes()
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
    public function test_getAccounts_returnsEmptyArray()
    {
        $entity = new IdcardIdentity();
        
        $this->assertSame([], $entity->getAccounts());
    }
} 