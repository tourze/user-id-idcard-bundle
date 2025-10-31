# User ID Idcard Bundle

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg?style=flat)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4-green.svg)](https://symfony.com/)
[![Build Status](https://github.com/tourze/php-monorepo/workflows/CI/badge.svg)](https://github.com/tourze/php-monorepo/actions)
[![Coverage](https://img.shields.io/codecov/c/github/tourze/php-monorepo?flag=user-id-idcard-bundle&style=flat-square)](https://codecov.io/gh/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle that provides Chinese ID card identity verification functionality 
as an extension to the user identity system.

## Features

- Chinese ID card identity management
- Integration with user identity system
- Doctrine ORM entity for ID card storage
- Service decorator pattern for extensibility
- Snowflake ID generation support
- Timestamp and blame tracking

## Installation

```bash
composer require tourze/user-id-idcard-bundle
```

## Requirements

- PHP 8.1+
- Symfony 6.4+
- Doctrine ORM 3.0+

## Dependencies

- `tourze/user-id-bundle`: Base identity system
- `tourze/doctrine-snowflake-bundle`: Snowflake ID generation
- `tourze/doctrine-timestamp-bundle`: Timestamp tracking
- `tourze/doctrine-user-bundle`: User blame tracking

## Quick Start

### 1. Enable the Bundle

Add the bundle to your `config/bundles.php`:

```php
return [
    // ... other bundles
    Tourze\UserIDIdcardBundle\UserIDIdcardBundle::class => ['all' => true],
];
```

### 2. Update Database Schema

```bash
php bin/console doctrine:schema:update --force
```

### 3. Basic Usage

```php
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;
use Tourze\UserIDIdcardBundle\Service\UserIdentityIdcardService;

// Create ID card identity
$idcardIdentity = new IdcardIdentity();
$idcardIdentity->setIdcard('110101199001011234');
$idcardIdentity->setUser($user);

// Find by ID card number
$identity = $userIdentityService->findByType(
    IdcardIdentity::IDENTITY_TYPE,
    '110101199001011234'
);

// Get all identities for a user
$identities = $userIdentityService->findByUser($user);
```

## Configuration

This bundle extends the `tourze/user-id-bundle` and requires no additional 
configuration. It automatically decorates the `UserIdentityService` to handle 
ID card identities.

## Advanced Usage

### Custom Validation

You can extend the ID card validation by creating custom validators:

```php
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CustomIdcardValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        // Custom validation logic
        if (!$this->isValidIdcard($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
    
    private function isValidIdcard(string $idcard): bool
    {
        // Implement your custom validation logic
        return true;
    }
}
```

### Service Integration

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
                // Process ID card identity
            }
        }
    }
}
```

## Entity Structure

The `IdcardIdentity` entity includes:

- `id`: Snowflake ID (primary key)
- `idcard`: Chinese ID card number (18 digits)
- `user`: Reference to user entity
- `createTime`: Creation timestamp
- `updateTime`: Last update timestamp
- `createBy`: User who created the record
- `updateBy`: User who last updated the record

## License

MIT