<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Base class for test cases.
 *
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.2.0
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var TestCaseContext The context of this test case.
     */
    protected $context;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->context = new TestCaseContext();
    }
}
