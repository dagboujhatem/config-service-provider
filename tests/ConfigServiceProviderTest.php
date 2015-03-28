<?php

namespace Dafiti\Silex;

use Silex\Application;

class ConfigServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldRegister()
    {
        $app = new Application();
        $app->register(new ConfigServiceProvider());

        $this->assertInstanceOf('\Dafiti\Silex\Configurator', $app['config']);
    }

    public function testShouldRegisterWithParams()
    {
        $app = new Application();
        $app->register(new ConfigServiceProvider(), [
            'config.paths' => [
                '/config'
            ],
            'config.cache_dir' => '/data/cache'
        ]);

        $this->assertCount(1, $app['config.paths']);
        $this->assertEquals('/data/cache', $app['config.cache_dir']);
    }

    /**
     * @dataProvider providerFileTypes
     */
    public function testShouldLoadConfigFile($fileType)
    {
        $app = new Application();
        $app->register(new ConfigServiceProvider(), [
            'config.paths' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'config'
            ]
        ]);

        $app['config']->load($fileType);

        $this->assertEquals('Dafiti', $app['config']['shop']);
        $this->assertEquals('http://www.dafiti.com.br', $app['config']['info']['website']);
    }

    public function providerFileTypes()
    {
        return [
            ['app.ini'],
            ['app.json'],
            ['app.php'],
            ['app.yml']
        ];
    }
}
