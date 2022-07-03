<?php declare(strict_types=1);
namespace Tonod\LogInformation\Core\Types;

class LogConfig
{
    private array $channels;
    private string $defaultChannel;

    public function __construct(string $defaultChannel, array $channels)
    {
        $this->defaultChannel = $defaultChannel;
        $this->channels = $channels;
    }

    public function getDefaultChannel(): string
    {
        return $this->defaultChannel;
    }

    public function getChannels(): array
    {
        return $this->channels;
    }
}
