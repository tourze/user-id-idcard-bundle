<?php

declare(strict_types=1);

namespace Tourze\UserIDIdcardBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\UserIDBundle\UserIDBundle;

class UserIDIdcardBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => [],
            UserIDBundle::class => [],
        ];
    }
}
