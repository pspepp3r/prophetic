<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Src\Services\ConfigService;

class ConfigServiceTest extends TestCase
{
    public function test_get_nested_settings(): void
    {
        $config = [
            'doctrine' => [
                'connection' => [
                    'user' => 'root'
                ]
            ]
        ];

        $config = new ConfigService($config);

        $this->assertEquals('root', $config->get('doctrine.connection.user'));
        $this->assertEquals(['user' => 'root'], $config->get('doctrine.connection'));
    }

    public function test_returns_default_on_not_found(): void
    {
        $config = [
            'doctrine' => [
                'connection' => [
                    'user' => 'root'
                ]
            ]
        ];

        $config = new ConfigService($config);

        $this->assertEquals('pdo_mysql', $config->get('doctrine.connection.driver', 'pdo_mysql'));
        $this->assertEquals('bar', $config->get('foo', 'bar'));
        $this->assertEquals('baz', $config->get('foo.bar', 'baz'));
    }

    public function test_returns_null_on_not_found(): void
    {
        $config = [
            'doctrine' => [
                'connection' => [
                    'user' => 'root'
                ]
            ]
        ];

        $config = new ConfigService($config);

        $this->assertNull($config->get('doctrine.connection.driver'));
        $this->assertNull($config->get('foo.bar'));
    }
}
