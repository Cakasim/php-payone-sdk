<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Config;

use Cakasim\Payone\Sdk\Config\Config;
use Cakasim\Payone\Sdk\Config\ConfigException;
use Cakasim\Payone\Sdk\Config\ConfigInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class ConfigTest extends TestCase
{
    /**
     * @testdox Set and get params
     */
    public function testConfig(): void
    {
        $config = new Config();

        $this->assertInstanceOf(ConfigInterface::class, $config);

        $config->set('param_1', '1');
        $config->set('param_2', 2);
        $config->set('param_3', true);
        $config->set('param_4', 'Two words!');
        $config->set('param_5', null);

        $this->assertTrue($config->has('param_1'));
        $this->assertTrue($config->has('param_2'));
        $this->assertTrue($config->has('param_3'));
        $this->assertTrue($config->has('param_4'));
        $this->assertTrue($config->has('param_5'));
        $this->assertFalse($config->has('no_param'));

        $this->assertEquals('1', $config->get('param_1'));
        $this->assertEquals(2, $config->get('param_2'));
        $this->assertEquals(true, $config->get('param_3'));
        $this->assertEquals('Two words!', $config->get('param_4'));
        $this->assertEquals(null, $config->get('param_5'));

        $this->expectException(ConfigException::class);
        $config->get('no_param');
    }
}
