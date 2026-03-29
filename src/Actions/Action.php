<?php

declare(strict_types=1);

namespace SDUI\Actions;

use SDUI\Contracts\Componentable;

final class Action implements Componentable
{
    private function __construct(
        private readonly string $type,
        private readonly mixed $payload = null,
    ) {}

    public static function navigate(string $route, array $params = []): self
    {
        return new self('navigate', array_filter([
            'route'  => $route,
            'params' => $params ?: null,
        ]));
    }

    public static function openUrl(string $url, bool $inApp = false): self
    {
        return new self('open_url', [
            'url'   => $url,
            'inApp' => $inApp,
        ]);
    }

    public static function emit(string $event, mixed $data = null): self
    {
        return new self('emit', array_filter([
            'event' => $event,
            'data'  => $data,
        ]));
    }

    public static function refresh(): self
    {
        return new self('refresh');
    }

    public static function custom(string $name, mixed $payload = null): self
    {
        return new self($name, $payload);
    }

    public function toArray(): array
    {
        return array_filter([
            'type'    => $this->type,
            'payload' => $this->payload,
        ], fn ($v) => $v !== null);
    }
}