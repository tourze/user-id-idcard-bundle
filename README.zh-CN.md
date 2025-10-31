# 用户身份证模块

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg?style=flat)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4-green.svg)](https://symfony.com/)
[![Build Status](https://github.com/tourze/php-monorepo/workflows/CI/badge.svg)](https://github.com/tourze/php-monorepo/actions)
[![Coverage](https://img.shields.io/codecov/c/github/tourze/php-monorepo?flag=user-id-idcard-bundle&style=flat-square)](https://codecov.io/gh/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

提供中国身份证身份验证功能的 Symfony Bundle，
作为用户身份系统的扩展。

## 功能特性

- 中国身份证身份管理
- 与用户身份系统集成
- Doctrine ORM 实体存储身份证信息
- 服务装饰器模式实现扩展性
- 支持雪花算法 ID 生成
- 时间戳和责任追踪

## 安装

```bash
composer require tourze/user-id-idcard-bundle
```

## 系统要求

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+

## 依赖项

- `tourze/user-id-bundle`: 基础身份系统
- `tourze/doctrine-snowflake-bundle`: 雪花算法 ID 生成
- `tourze/doctrine-timestamp-bundle`: 时间戳追踪
- `tourze/doctrine-user-bundle`: 用户责任追踪

## 快速开始

### 1. 启用 Bundle

在 `config/bundles.php` 中添加：

```php
return [
    // ... 其他 bundle
    Tourze\UserIDIdcardBundle\UserIDIdcardBundle::class => ['all' => true],
];
```

### 2. 更新数据库架构

```bash
php bin/console doctrine:schema:update --force
```

### 3. 基本用法

```php
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;
use Tourze\UserIDIdcardBundle\Service\UserIdentityIdcardService;

// 创建身份证身份
$idcardIdentity = new IdcardIdentity();
$idcardIdentity->setIdcard('110101199001011234');
$idcardIdentity->setUser($user);

// 根据身份证号查找身份
$identity = $userIdentityService->findByType(
    IdcardIdentity::IDENTITY_TYPE,
    '110101199001011234'
);

// 获取用户的所有身份
$identities = $userIdentityService->findByUser($user);
```

## 配置

本 Bundle 扩展了 `tourze/user-id-bundle`，无需额外配置。
它会自动装饰 `UserIdentityService` 来处理身份证身份。

## 高级用法

### 自定义验证

你可以通过创建自定义验证器来扩展身份证验证：

```php
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CustomIdcardValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        // 自定义验证逻辑
        if (!$this->isValidIdcard($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
    
    private function isValidIdcard(string $idcard): bool
    {
        // 实现你的自定义验证逻辑
        return true;
    }
}
```

### 服务集成

```php
use Tourze\UserIDIdcardBundle\Service\UserIdentityIdcardService;

class YourService
{
    public function __construct(
        private UserIdentityIdcardService $identityService
    ) {}
    
    public function processUserIdentity(UserInterface $user): void
    {
        $identities = $this->identityService->findByUser($user);
        foreach ($identities as $identity) {
            if ($identity->getIdentityType() === IdcardIdentity::IDENTITY_TYPE) {
                // 处理身份证身份
            }
        }
    }
}
```

## 实体结构

`IdcardIdentity` 实体包含：

- `id`: 雪花算法 ID（主键）
- `idcard`: 中国身份证号码（18位）
- `user`: 用户实体引用
- `createTime`: 创建时间
- `updateTime`: 最后更新时间
- `createBy`: 创建者
- `updateBy`: 最后更新者

## 许可证

MIT
