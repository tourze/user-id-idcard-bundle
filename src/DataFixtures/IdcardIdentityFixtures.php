<?php

declare(strict_types=1);

namespace Tourze\UserIDIdcardBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;
use Tourze\UserServiceContracts\UserServiceConstants;

/**
 * 身份证认证数据填充
 *
 * 为系统用户创建身份证认证记录，用于演示身份认证功能
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class IdcardIdentityFixtures extends Fixture implements FixtureGroupInterface
{
    // 身份证认证引用常量
    public const ADMIN_IDCARD_IDENTITY_REFERENCE = 'admin-idcard-identity';
    public const MODERATOR_IDCARD_IDENTITY_REFERENCE = 'moderator-idcard-identity';
    public const USER_IDCARD_IDENTITY_REFERENCE = 'user-idcard-identity';

    public static function getGroups(): array
    {
        return [
            UserServiceConstants::USER_FIXTURES_NAME,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        // 创建测试数据
        $idcardIdentity = new IdcardIdentity();
        $idcardIdentity->setIdcard('110101199001011234');
        $manager->persist($idcardIdentity);

        $manager->flush();

        $this->addReference(self::ADMIN_IDCARD_IDENTITY_REFERENCE, $idcardIdentity);
    }
}
