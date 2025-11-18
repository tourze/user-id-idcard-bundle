<?php

declare(strict_types=1);

namespace Tourze\UserIDIdcardBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use Tourze\UserIDIdcardBundle\Controller\Admin\IdcardIdentityCrudController;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;

/**
 * 身份证管理控制器测试
 * @internal
 */
#[CoversClass(IdcardIdentityCrudController::class)]
#[RunTestsInSeparateProcesses]
final class IdcardIdentityCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): IdcardIdentityCrudController
    {
        return new IdcardIdentityCrudController();
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'id' => ['ID'];
        yield 'idcard' => ['身份证号'];
        yield 'user' => ['关联用户'];
        yield 'createTime' => ['创建时间'];
        yield 'updateTime' => ['更新时间'];
        yield 'createdBy' => ['创建人'];
        yield 'updatedBy' => ['更新人'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'idcard_field' => ['idcard'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield 'idcard_field' => ['idcard'];
    }

    public function testConfigureFields(): void
    {
        $controller = new IdcardIdentityCrudController();
        $fields = $controller->configureFields('index');

        self::assertIsIterable($fields);
        self::assertNotEmpty($fields);
    }

    /**
     * 重写基类的必填字段检查，因为不同实体有不同的必填字段
     */

    /**
     * 测试表单验证错误 - 提交空表单应返回验证错误
     */
    #[Test]
    public function testValidationErrors(): void
    {
        // 创建一个空的身份证实体来测试验证
        $idcardIdentity = new IdcardIdentity();

        // 使用Symfony的验证器测试实体验证
        $validator = self::getService('Symfony\Component\Validator\Validator\ValidatorInterface');
        $violations = $validator->validate($idcardIdentity);

        // 验证必填字段有验证错误
        $this->assertGreaterThan(0, count($violations), '身份证实体应该有验证错误（身份证号字段为必填）');

        // 检查是否有idcard字段的验证错误（NotBlank约束）
        $hasIdcardError = false;
        foreach ($violations as $violation) {
            if ('idcard' === $violation->getPropertyPath()) {
                $hasIdcardError = true;
                // 验证错误消息应该包含"should not be blank"或相似的内容
                $this->assertStringContainsString('不能为空', (string) $violation->getMessage(), 'idcard字段验证错误应该包含不能为空的信息');
                break;
            }
        }
        $this->assertTrue($hasIdcardError, '应该有idcard字段的NotBlank验证错误');
    }
}
