<?php

namespace Tourze\UserIDIdcardBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\UserIDIdcardBundle\UserIDIdcardBundle;

class UserIDIdcardBundleTest extends TestCase
{
    /**
     * 测试 UserIDIdcardBundle 类正确继承 Bundle 类
     */
    public function test_inheritance_extendsBundleClass()
    {
        $bundle = new UserIDIdcardBundle();

        $this->assertInstanceOf(Bundle::class, $bundle);
    }
}
