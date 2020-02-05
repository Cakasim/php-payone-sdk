<?php

declare(strict_types=1);

namespace Cakasim\Payone\Sdk\Tests\Api\Format;

use Cakasim\Payone\Sdk\Api\Format\Decoder;
use Cakasim\Payone\Sdk\Api\Format\DecoderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Fabian Böttcher <me@cakasim.de>
 * @since 0.1.0
 */
class DecoderTest extends TestCase
{
    /**
     * @var DecoderInterface The decoder to test.
     */
    protected $decoder;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->decoder = new Decoder();
    }

    /**
     * Provides data for decoding tests.
     *
     * @return array
     */
    public function dataTestDecoding(): array
    {
        return [
            [
                join("\n", [
                    'param1=value1',
                    'param2=value2',
                    'param_with_underscore=value_3',
                    'paramWithCamelCase=valueFour',
                    'paramWithEncodedValue=Hello World',
                ]),
                [
                    'param1' => 'value1',
                    'param2' => 'value2',
                    'param_with_underscore' => 'value_3',
                    'paramWithCamelCase' => 'valueFour',
                    'paramWithEncodedValue' => 'Hello World',
                ],
            ],

            [
                join("\n", [
                    'regular_param=JohnDoe',
                    'nested_param[1]=One',
                    'nested_param[2]=Two',
                    'nested_param[3]=Three',
                    'deep_nested_param[level0][level1][level2]=DeepValue',
                    'deep_nested_param[levelA]=xyz',
                ]),
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
            ],
        ];
    }

    /**
     * Tests data decoding.
     *
     * @dataProvider dataTestDecoding
     * @param string $data The data to decode.
     * @param array $expected The expected decoding result.
     */
    public function testDecoding(string $data, array $expected): void
    {
        $this->assertEquals($expected, $this->decoder->decode($data));
    }
}
