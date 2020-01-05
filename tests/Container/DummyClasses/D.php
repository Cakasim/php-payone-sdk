<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Container\DummyClasses;

use Psr\Container\ContainerInterface;

class D
{
    public function __construct($no, bool $valid, $param, ContainerInterface $type, $hints = null)
    {
    }
}
