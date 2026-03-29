<?php

declare(strict_types=1);

namespace SDUI\Components;

use SDUI\Contracts\Componentable;

// ── Text ───────────────────────────────────────────────────────────────────

final class Text extends Component
{
    private function __construct(string $text)
    {
        $this->props['text'] = $text;
    }

    public static function make(string $text): self
    {
        return new self($text);
    }

    protected function type(): string { return 'text'; }

    public function variant(string $variant): self
    {
        // display | heading | subheading | body | caption | label
        return $this->prop('variant', $variant);
    }

    public function muted(): self { return $this->prop('muted', true); }

    public function bold(): self { return $this->prop('bold', true); }

    public function align(string $align): self { return $this->prop('align', $align); }

    public function color(string $color): self { return $this->prop('color', $color); }
}

// ── Button ─────────────────────────────────────────────────────────────────

final class Button extends Component
{
    private function __construct(string $label)
    {
        $this->props['label'] = $label;
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    protected function type(): string { return 'button'; }

    public function variant(string $variant): self
    {
        // primary | secondary | ghost | danger
        return $this->prop('variant', $variant);
    }

    public function disabled(): self { return $this->prop('disabled', true); }

    public function loading(): self { return $this->prop('loading', true); }

    public function icon(string $name, string $position = 'left'): self
    {
        $this->prop('icon', $name);
        return $this->prop('iconPosition', $position);
    }

    public function destructive(): self { return $this->variant('danger'); }

    public function fullWidth(): self { return $this->prop('fullWidth', true); }
}

// ── Image ──────────────────────────────────────────────────────────────────

final class Image extends Component
{
    private function __construct(string $src)
    {
        $this->props['src'] = $src;
    }

    public static function make(string $src): self
    {
        return new self($src);
    }

    protected function type(): string { return 'image'; }

    public function width(int $width): self { return $this->prop('width', $width); }

    public function height(int $height): self { return $this->prop('height', $height); }

    public function rounded(int $radius = 8): self { return $this->prop('rounded', $radius); }

    public function circle(): self { return $this->prop('circle', true); }

    public function alt(string $text): self { return $this->prop('alt', $text); }

    public function resizeMode(string $mode): self { return $this->prop('resizeMode', $mode); }

    public function aspectRatio(float $ratio): self { return $this->prop('aspectRatio', $ratio); }
}

// ── Stack ──────────────────────────────────────────────────────────────────

final class Stack extends Component
{
    private function __construct(string $direction, int $gap = 0)
    {
        $this->props['direction'] = $direction;

        if ($gap > 0) {
            $this->props['gap'] = $gap;
        }
    }

    public static function vertical(int $gap = 0): self
    {
        return new self('vertical', $gap);
    }

    public static function horizontal(int $gap = 0): self
    {
        return new self('horizontal', $gap);
    }

    protected function type(): string { return 'stack'; }

    public function padding(int $all): self { return $this->prop('padding', $all); }

    public function paddingH(int $h): self { return $this->prop('paddingH', $h); }

    public function paddingV(int $v): self { return $this->prop('paddingV', $v); }

    public function align(string $align): self { return $this->prop('align', $align); }

    public function justify(string $justify): self { return $this->prop('justify', $justify); }

    public function wrap(): self { return $this->prop('wrap', true); }

    public function scrollable(): self { return $this->prop('scrollable', true); }
}

// ── Card ───────────────────────────────────────────────────────────────────

final class Card extends Component
{
    private function __construct() {}

    public static function make(): self
    {
        return new self();
    }

    protected function type(): string { return 'card'; }

    public function padding(int $padding): self { return $this->prop('padding', $padding); }

    public function elevated(): self { return $this->prop('elevated', true); }

    public function outlined(): self { return $this->prop('outlined', true); }
}

// ── Spacer ─────────────────────────────────────────────────────────────────

final class Spacer extends Component
{
    private function __construct(int $size)
    {
        $this->props['size'] = $size;
    }

    public static function make(int $size = 16): self
    {
        return new self($size);
    }

    protected function type(): string { return 'spacer'; }
}

// ── Divider ────────────────────────────────────────────────────────────────

final class Divider extends Component
{
    private function __construct() {}

    public static function make(): self
    {
        return new self();
    }

    protected function type(): string { return 'divider'; }

    public function color(string $color): self { return $this->prop('color', $color); }

    public function thickness(int $px): self { return $this->prop('thickness', $px); }
}

// ── Avatar ─────────────────────────────────────────────────────────────────

final class Avatar extends Component
{
    private function __construct(string $name)
    {
        $this->props['name'] = $name;
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    protected function type(): string { return 'avatar'; }

    public function src(string $url): self { return $this->prop('src', $url); }

    public function size(int $size): self { return $this->prop('size', $size); }

    public function color(string $color): self { return $this->prop('color', $color); }
}

// ── Badge ──────────────────────────────────────────────────────────────────

final class Badge extends Component
{
    private function __construct(string $label)
    {
        $this->props['label'] = $label;
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    protected function type(): string { return 'badge'; }

    public function color(string $color): self { return $this->prop('color', $color); }

    public function variant(string $variant): self { return $this->prop('variant', $variant); }
}

// ── ListItem ───────────────────────────────────────────────────────────────

final class ListItem extends Component
{
    private function __construct(string $label)
    {
        $this->props['label'] = $label;
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    protected function type(): string { return 'list_item'; }

    public function subtitle(string $text): self { return $this->prop('subtitle', $text); }

    public function icon(string $name): self { return $this->prop('icon', $name); }

    public function destructive(): self { return $this->prop('destructive', true); }

    public function disclosure(): self { return $this->prop('disclosure', true); }

    public function trailing(string $text): self { return $this->prop('trailing', $text); }
}

// ── StatGrid ───────────────────────────────────────────────────────────────

final class StatGrid extends Component
{
    private function __construct(array $stats)
    {
        $this->props['stats'] = array_map(
            fn ($stat) => [
                'label' => (string) $stat['label'],
                'value' => (string) $stat['value'],
            ],
            $stats
        );
    }

    public static function make(array $stats): self
    {
        return new self($stats);
    }

    protected function type(): string { return 'stat_grid'; }

    public function columns(int $cols): self { return $this->prop('columns', $cols); }
}

// ── Native ─────────────────────────────────────────────────────────────────
// Escape hatch — renders a fully native component by name.
// The client handles everything; SDUI only positions it in the layout.

final class Native extends Component
{
    private function __construct(string $name)
    {
        $this->props['name'] = $name;
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    protected function type(): string { return 'native'; }

    public function props(array $props): self
    {
        foreach ($props as $key => $value) {
            $this->props[$key] = $value;
        }

        return $this;
    }

    public function height(int $height): self { return $this->prop('height', $height); }
}

// ── Grid ───────────────────────────────────────────────────────────────────

final class Grid extends Component
{
    private function __construct(int $columns)
    {
        $this->props['columns'] = $columns;
    }

    public static function make(int $columns = 2): self
    {
        return new self($columns);
    }

    protected function type(): string { return 'grid'; }

    public function gap(int $gap): self { return $this->prop('gap', $gap); }

    public function padding(int $padding): self { return $this->prop('padding', $padding); }
}

// ── Conditional ────────────────────────────────────────────────────────────
// Server-side conditional — only serializes the matching branch.
// The client never sees the condition; it just sees one component tree.

final class Conditional extends Component
{
    private function __construct(
        private readonly bool $condition,
    ) {}

    public static function when(bool $condition): self
    {
        return new self($condition);
    }

    protected function type(): string { return 'stack'; }

    public function then(Componentable $component): self
    {
        if ($this->condition) {
            $this->children = [$component];
        }

        return $this;
    }

    public function otherwise(Componentable $component): self
    {
        if (!$this->condition) {
            $this->children = [$component];
        }

        return $this;
    }

    public function toArray(): array
    {
        if (empty($this->children)) {
            return [];
        }

        // If only one child, unwrap it — no need for a wrapper stack
        if (count($this->children) === 1) {
            return $this->children[0]->toArray();
        }

        return parent::toArray();
    }
}