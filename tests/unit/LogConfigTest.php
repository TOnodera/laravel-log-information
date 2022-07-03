<?php declare(strict_types=1);
namespace Tests\Unit;

use Exception;
use \Orchestra\Testbench\TestCase;
use Tonod\LogInformation\Core\LogConfig;
use Tonod\LogInformation\Exceptions\ApplicationException;

class LogConfigTest extends TestCase
{
    /**
     * @test
     */
    public function デフォルトのチャンネルを取得できる()
    {
        $logConfig = new LogConfig(['default' => 'stack']);
        $this->assertSame('stack', $logConfig->getDefaultChannel());
    }

    public function デフォルトの設定が取得できない場合はApplicationExceptionとする()
    {
        $logConfig = new LogConfig([]);
        try {
            $logConfig->getDefaultChannel();
        } catch (ApplicationException $e) {
            $this->assertTrue($e instanceof ApplicationException);
        }
    }

    /**
     * @test
     */
    public function チャンネルデータを取得できる()
    {
        $channels = [
            'channels' => [
                'stack' => [
                    'driver' => 'stack',
                    'channels' => ['daily'],
                    'ignore_exceptions' => false,
                ],

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

                'stderr' => [
                    'driver' => 'monolog',
                    'level' => env('LOG_LEVEL', 'debug'),
                    'handler' => StreamHandler::class,
                    'formatter' => env('LOG_STDERR_FORMATTER'),
                    'with' => [
                        'stream' => 'php://stderr',
                    ],
                ],

                'syslog' => [
                    'driver' => 'syslog',
                    'level' => env('LOG_LEVEL', 'debug'),
                ],

                'errorlog' => [
                    'driver' => 'errorlog',
                    'level' => env('LOG_LEVEL', 'debug'),
                ],

                'null' => [
                    'driver' => 'monolog',
                    'handler' => NullHandler::class,
                ],

                'emergency' => [
                    'path' => storage_path('logs/laravel.log'),
                ],
            ]
        ];
        $logConfig = new LogConfig($channels);
        $logConfig->getChannels()->contains(function ($value, $key) use ($channels) {
            $this->assertTrue(key_exists($key, $channels['channels']));
        });
    }

    /**
     * @test
     */
    public function チャンネルデータが取得できない場合はApplicationExceptionとする()
    {
        $logConfig = new LogConfig([]);
        try {
            $logConfig->getChannels();
        } catch (Exception $e) {
            $this->assertTrue($e instanceof ApplicationException);
        }
    }
}
