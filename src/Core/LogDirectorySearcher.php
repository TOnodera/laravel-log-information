<?php declare(strict_types=1);
namespace Tonod\LogInformation\Core;

use Illuminate\Support\Collection;
use Tonod\LogInformation\Core\LogConfig;
use Tonod\LogInformation\Exceptions\ApplicationException;

class LogDirectorySearcher
{
    private LogConfig $logConfig;

    public function __construct(LogConfig $logConfig)
    {
        $this->logConfig = $logConfig;
    }

    /**
     * ファイルの存在するディレクトリ一覧を返す
     *
     * @return Collection
     * @throws ApplicationException
     */
    public function getLogDirectoryPaths(): Collection
    {
        $defaultChannel = $this->logConfig->getDefaultChannel();

        $channels = $this->logConfig->getChannels();
        if ($defaultChannel === 'stack') {
            $filePaths = $channels->filter(function ($channel) {
                return key_exists('path', $channel);
            })->map(function ($channel) {
                return $channel['path'];
            });

            return $filePaths->map(function ($path) {
                return $this->removeUpperStoragePath(pathinfo($path)['dirname']);
            });
        }

        $logDirs = $channels->filter(function ($channel, $key) use ($defaultChannel) {
            return $defaultChannel === $key;
        })->map(function ($channel) {
            return $this->removeUpperStoragePath(pathinfo($channel['path'])['dirname']);
        });

        return $logDirs ?? throw new ApplicationException();
    }

    /**
     * ストレージディレクトリより上位階層のディレクトリは表示させたくないのでここで消す。
     *
     * @param string $path
     * @return void
     */
    private function removeUpperStoragePath(string $path)
    {
        return trim(str_replace(storage_path(), '', $path), '/');
    }
}
