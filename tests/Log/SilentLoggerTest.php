<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Log;

use Cakasim\Payone\Sdk\Log\SilentLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionClass;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class SilentLoggerTest extends TestCase
{
    /**
     * @testdox The logger is silent
     */
    public function testSilentLogger(): void
    {
        $logger = new SilentLogger();
        $this->assertInstanceOf(LoggerInterface::class, $logger);

        // Inspect SilentLogger to be actually silent.
        // Check for exactly 3 lines of code.
        $class = new ReflectionClass($logger);
        $method = $class->getMethod('log');
        $this->assertEquals(3, $method->getEndLine() - $method->getStartLine());

        // Check if the lines of code are silent.
        $source = file($class->getFileName());
        $this->assertRegExp('~^\s*{\s*$~', $source[$method->getStartLine()]);
        $this->assertRegExp('~^\s*//~', $source[$method->getStartLine() + 1]);
        $this->assertRegExp('~^\s*}\s*$~', $source[$method->getStartLine() + 2]);
    }
}
