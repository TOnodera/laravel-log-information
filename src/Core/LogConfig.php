<?php declare(strict_types=1);
namespace Tonod\LogInformation\Core;

use Illuminate\Support\Collection;
use Tonod\LogInformation\Exceptions\ApplicationException;

class LogConfig
{
    private array $logging;

    public function __construct(array $logging)
    {
        $this->logging = $logging;
    }

    public function getDefaultChannel(): string
    {
        if (key_exists('default', $this->logging)) {
            return $this->logging['default'];
        }
        throw new ApplicationException();
    }

    public function getChannels(): Collection
    {
        if (key_exists('channels', $this->logging)) {
            return Collection::make($this->logging['channels']);
        }
        throw new ApplicationException();
    }
}
