<?php declare(strict_types=1);
namespace Tonod\LogInformation\Core\LogFileSearcher;

use Exception;
use Illuminate\Support\Collection;
use Tonod\LogInformation\Core\LogConfig;
use Tonod\LogInformation\Exceptions\ApplicationException;

class LogFileSearcher
{
    private LogConfig $logConfig;

    public function __construct(LogConfig $logConfig)
    {
        if (!$logConfig->getDefaultChannel()
            || !$logConfig->getChannels()
            || count($logConfig->getChannels()) == 0) {
            // 例外投げる
        }
        $this->LogConfig = $logConfig;
    }

    /**
     * ファイルの存在するディレクトリ一覧を返す
     *
     * @return array
     * @throws ApplicationException
     */
    public function getLogFileDirs(): Collection
    {
        $defaultChannel = $this->logConfig->getDefaultChannel();
        $logDirs = null;

        $channels = $this->logConfig->getChannels();
        if ($defaultChannel === 'stack') {
            $filePaths = $channels->map(function ($channel) {
                return $channel['path'];
            });
            $logDirs = $filePaths->map(function ($path) {
                return pathinfo($path)['dirname'];
            });
            return $logDirs;
        }

        $logDirs = $channels->filter(function ($channel) use ($defaultChannel) {
            return $defaultChannel === $channel;
        });

        return $logDirs ?? throw new ApplicationException();
    }
}
