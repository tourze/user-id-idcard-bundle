<?php

declare(strict_types=1);

namespace UserIdIdcardBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\UserIDIdcardBundle\UserIDIdcardBundle;

/**
 * @internal
 */
#[CoversClass(UserIDIdcardBundle::class)]
#[RunTestsInSeparateProcesses]
final class UserIDIdcardBundleTest extends AbstractBundleTestCase
{
}
