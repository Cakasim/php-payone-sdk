<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Api\Format;

use Cakasim\Payone\Sdk\Api\Format\Encoder;
use Cakasim\Payone\Sdk\Api\Format\EncoderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class EncoderTest extends TestCase
{
    /**
     * @var EncoderInterface The encoder to test.
     */
    protected $encoder;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->encoder = new Encoder();
    }

    /**
     * Provides data for encoding tests.
     *
     * @return array
     */
    public function dataTestEncoding(): array
    {
        return [
            [
                [
                    'param1' => 'value1',
                    'param2' => 'value2',
                    'param_with_underscore' => 'value_3',
                    'paramWithCamelCase' => 'valueFour',
                    'paramWithEncodedValue' => 'Hello World',
                ],
                'param1=value1&param2=value2&param_with_underscore=value_3&' .
                'paramWithCamelCase=valueFour&paramWithEncodedValue=Hello+World',
            ],

            [
                [
                    'regular_param' => 'JohnDoe',
                    'nested_param' => [
                        1 => 'One',
                        2 => 'Two',
                        3 => 'Three',
                    ],
                    'deep_nested_param' => [
                        'level0' => [
                            'level1' => [
                                'level2' => 'DeepValue',
                            ]
                        ],
                        'levelA' => 'xyz',
                    ]
                ],
                'regular_param=JohnDoe&nested_param%5B1%5D=One&nested_param%5B2%5D=Two&nested_param%5B3%5D=Three&' .
                'deep_nested_param%5Blevel0%5D%5Blevel1%5D%5Blevel2%5D=DeepValue&deep_nested_param%5BlevelA%5D=xyz',
            ],
        ];
    }

    /**
     * Tests data encoding.
     *
     * @dataProvider dataTestEncoding
     * @param array $data The data to encode.
     * @param string $expected The expected encoding result.
     */
    public function testEncoding(array $data, string $expected): void
    {
        $this->assertEquals($expected, $this->encoder->encode($data));
    }
}
