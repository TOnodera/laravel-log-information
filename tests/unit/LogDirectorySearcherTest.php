<?php declare(strict_types=1);
namespace Tests\Unit;

use \Orchestra\Testbench\TestCase;
use Tonod\LogInformation\Core\LogConfig;
use Tonod\LogInformation\Core\LogDirectorySearcher;

class LogDirectorySearcherTest extends TestCase
{
    /**
     * @test
     */
    public function 単一チャンネルからディレクトリ名を取得できる()
    {
        $logConfig = new LogConfig([
            'channels' => [
                'test' => ['path' => 'path/to/file/laravel.log']
            ],
            'default' => 'test'
        ]);

        $logDirectorySearcher = new LogDirectorySearcher($logConfig);
        $paths = $logDirectorySearcher->getLogDirectoryPaths();
        $this->assertSame(1, $paths->count());

        $paths->each(function ($path) {
            $this->assertTrue('path/to/file' == $path);
        });
    }

    /**
     * @test
     */
    public function チャンネルがstackの場合は複数のディレクトリ情報を取得できる()
    {
        $logConfig = new LogConfig([
            'channels' => [
                'single' => [
                    'driver' => 'single',
                    'path' => storage_path('logs/laravel.log'),
                    'level' => env('LOG_LEVEL', 'debug'),
                ],

                'daily' => [
                    'driver' => 'daily',
                    'path' => storage_path('path/to/logs/' . date('Y-m-d') . '_laravel.log'),
                    'level' => env('LOG_LEVEL', 'debug'),
                    'days' => 14,
                ]
            ],
            'default' => 'stack'
        ]);

        $logDirectorySearcher = new LogDirectorySearcher($logConfig);
        $paths = $logDirectorySearcher->getLogDirectoryPaths();
        $this->assertSame(2, $paths->count());

        $paths->each(function ($path) {
            $this->assertTrue(in_array($path, [
                'logs',
                'path/to/logs'
            ]));
        });
    }

    /**
     * @test
     */
    public function チャンネルにpathの指定がない場合はそのチャンネルは無視する()
    {
        $channels = [
            'default' => 'stack',
            'channels' => [
                'single' => [
                    'driver' => 'single',
                    'path' => storage_path('logs/laravel.log'),
                    'level' => env('LOG_LEVEL', 'debug'),
                ],

                'daily' => [
                    'driver' => 'daily',
                    'path' => storage_path('logs/' . date('Y-m-d') . '_laravel.log'),
                    'level' => env('LOG_LEVEL', 'debug'),
                    'days' => 14,
                ],

                'slack' => [
                    'driver' => 'slack',
                    'url' => env('LOG_SLACK_WEBHOOK_URL'),
                    'username' => 'Laravel Log',
                    'emoji' => ':boom:',
                    'level' => env('LOG_LEVEL', 'critical'),
                ],

                'papertrail' => [
                    'driver' => 'monolog',
                    'level' => env('LOG_LEVEL', 'debug'),
                    'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
                    'handler_with' => [
                        'host' => env('PAPERTRAIL_URL'),
                        'port' => env('PAPERTRAIL_PORT'),
                        'connectionString' => 'tls://' . env('PAPERTRAIL_URL') . ':' . env('PAPERTRAIL_PORT'),
                    ],
                ],
            ]];

        $logConfig = new LogConfig($channels);
        $logDirectorySearcher = new LogDirectorySearcher($logConfig);
        $paths = $logDirectorySearcher->getLogDirectoryPaths();
        $this->assertSame(2, $paths->count());
    }
}
