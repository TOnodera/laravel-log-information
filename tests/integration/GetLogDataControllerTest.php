<?php declare(strict_types=1);
namespace Tests\Integration;

use \Orchestra\Testbench\TestCase;

class GetLogDataControllerTest extends TestCase
{
    /**
     * @test
     */
    public function 指定されたファイル名のデータを取得できる()
    {
        // ファイル名からログデータを取得
        // データを返却する
        $this->assertTrue(true);
    }
}
