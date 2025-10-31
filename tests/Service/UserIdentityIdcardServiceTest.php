<?php

namespace Tourze\UserIDIdcardBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;
use Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository;
use Tourze\UserIDIdcardBundle\Service\UserIdentityIdcardService;

/**
 * @internal
 */
#[CoversClass(UserIdentityIdcardService::class)]
#[RunTestsInSeparateProcesses]
final class UserIdentityIdcardServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 集成测试设置方法
    }

    private function getUserIdentityIdcardService(): UserIdentityIdcardService
    {
        return self::getService(UserIdentityIdcardService::class);
    }

    /**
     * 测试 findByType 方法当类型为身份证且找到记录时
     */
    public function testFindByTypeWhenTypeIsIdcardAndIdentityFound(): void
    {
        // 创建一个身份证记录
        $identity = new IdcardIdentity();
        $identity->setIdcard('110101199001011234');

        $repository = self::getService(IdcardIdentityRepository::class);
        $repository->save($identity);

        $service = $this->getUserIdentityIdcardService();
        $type = IdcardIdentity::IDENTITY_TYPE;
        $value = '110101199001011234';

        $result = $service->findByType($type, $value);

        $this->assertInstanceOf(IdcardIdentity::class, $result);
        $this->assertSame($value, $result->getIdcard());
    }

    /**
     * 测试 findByType 方法当类型为身份证但未找到记录时
     */
    public function testFindByTypeWhenTypeIsIdcardButIdentityNotFound(): void
    {
        $service = $this->getUserIdentityIdcardService();
        $type = IdcardIdentity::IDENTITY_TYPE;
        $value = '999999999999999999';

        $result = $service->findByType($type, $value);

        // 应该返回 null 或者调用内部服务的结果
        $this->assertNull($result);
    }

    /**
     * 测试 findByType 方法当类型不是身份证时
     */
    public function testFindByTypeWhenTypeIsNotIdcard(): void
    {
        $service = $this->getUserIdentityIdcardService();
        $type = 'email';
        $value = 'test@example.com';

        $result = $service->findByType($type, $value);

        // 对于非身份证类型，应该委托给内部服务处理，通常返回 null
        $this->assertNull($result);
    }

    /**
     * 测试 findByUser 方法
     */
    public function testFindByUser(): void
    {
        $user = $this->createNormalUser('test@example.com', 'password');

        // 为用户创建身份证记录
        $identity1 = new IdcardIdentity();
        $identity1->setIdcard('110101199001011235');
        $identity1->setUser($user);

        $identity2 = new IdcardIdentity();
        $identity2->setIdcard('110101199001011236');
        $identity2->setUser($user);

        $repository = self::getService(IdcardIdentityRepository::class);
        $repository->save($identity1);
        $repository->save($identity2);

        $service = $this->getUserIdentityIdcardService();
        $result = iterator_to_array($service->findByUser($user));

        $this->assertGreaterThanOrEqual(2, count($result));

        // 检查身份证记录是否包含在结果中
        $foundIdcards = array_filter($result, fn ($identity) => $identity instanceof IdcardIdentity);
        $this->assertGreaterThanOrEqual(2, count($foundIdcards));
    }
}
