<?php

declare(strict_types=1);

namespace SDUI\Components;

use SDUI\Actions\Action;
use SDUI\Contracts\Componentable;
use SDUI\Exceptions\SDUIException;

abstract class Component implements Componentable
{
    protected array  $props    = [];
    protected ?Action $action  = null;
    protected array  $children = [];

    abstract protected function type(): string;

    // ── Action helpers ─────────────────────────────────────────────────────

    public function navigateTo(string $route, array $params = []): static
    {
        $this->action = Action::navigate($route, $params);
        return $this;
    }

    public function openUrl(string $url, bool $inApp = false): static
    {
        $this->action = Action::openUrl($url, $inApp);
        return $this;
    }

    public function emit(string $event, mixed $data = null): static
    {
        $this->action = Action::emit($event, $data);
        return $this;
    }

    public function action(string $type, mixed $payload = null): static
    {
        $this->action = Action::custom($type, $payload);
        return $this;
    }

    // ── Children ───────────────────────────────────────────────────────────

    public function add(Componentable|array $component): static
    {
        if (is_array($component)) {
            foreach ($component as $c) {
                $this->assertComponentable($c);
                $this->children[] = $c;
            }
        } else {
            $this->children[] = $component;
        }

        return $this;
    }

    // ── Serialization ──────────────────────────────────────────────────────

    public function toArray(): array
    {
        $node = ['type' => $this->type()];

        if (!empty($this->props)) {
            $node['props'] = $this->props;
        }

        if (!empty($this->children)) {
            $node['children'] = array_map(
                fn (Componentable $c) => $c->toArray(),
                $this->children
            );
        }

        if ($this->action !== null) {
            $node['action'] = $this->action->toArray();
        }

        return $node;
    }

    // ── Internal ───────────────────────────────────────────────────────────

    protected function prop(string $key, mixed $value): static
    {
        $this->props[$key] = $value;
        return $this;
    }

    private function assertComponentable(mixed $value): void
    {
        if (!$value instanceof Componentable) {
            throw new SDUIException(
                sprintf('Expected Componentable, got %s', get_debug_type($value))
            );
        }
    }
}