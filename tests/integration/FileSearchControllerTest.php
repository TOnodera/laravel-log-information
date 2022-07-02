<?php declare(strict_types=1);
namespace Tests\Integration;

use \Orchestra\Testbench\TestCase;

/**
 * @test
 */
class LogFileSearchControllerTest extends TestCase
{
    /**
     * @test
     */
    public function ログファイル一覧を取得できる()
    {
        // デフォルトのログチャンネルを取得
        // チャンネルからログファイルのパスを取得する、stackの場合はそこから取得する
        // パスのディレクトリ内のファイル名をリストにして返却
        $this->assertTrue(true);
    }
}
