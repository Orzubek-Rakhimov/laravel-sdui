<?php

declare(strict_types=1);

namespace SDUI;

use Illuminate\Http\JsonResponse;
use SDUI\Components\Component;
use SDUI\Components\Stack;
use SDUI\Contracts\Componentable;
use SDUI\Exceptions\SDUIException;

final class Screen
{
    private const PROTOCOL_VERSION = 1;

    private ?string $title     = null;
    private array   $meta      = [];
    private array   $children  = [];
    private ?string $clientVersion = null;

    private function __construct(private readonly string $id) {}

    public static function make(string $id): self
    {
        return new self($id);
    }

    // ── Builder ────────────────────────────────────────────────────────────

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function meta(string $key, mixed $value): self
    {
        $this->meta[$key] = $value;
        return $this;
    }

    public function add(Componentable|array $component): self
    {
        if (is_array($component)) {
            foreach ($component as $c) {
                $this->assertComponentable($c);
                $this->children[] = $c;
            }
        } else {
            $this->assertComponentable($component);
            $this->children[] = $component;
        }

        return $this;
    }

    /**
     * Conditionally add a component.
     *
     * Usage:
     *   ->addIf($isAdmin, Banner::make('Admin mode'))
     */
    public function addIf(bool $condition, Componentable $component): self
    {
        if ($condition) {
            $this->children[] = $component;
        }

        return $this;
    }

    /**
     * Add multiple components from a collection.
     *
     * Usage:
     *   ->addMany($users, fn($u) => UserCard::make($u))
     */
    public function addMany(iterable $items, callable $mapper): self
    {
        foreach ($items as $item) {
            $component = $mapper($item);
            $this->assertComponentable($component);
            $this->children[] = $component;
        }

        return $this;
    }

    // ── Output ─────────────────────────────────────────────────────────────

    public function toArray(): array
    {
        $root = $this->buildRoot();

        $screen = array_filter([
            'id'    => $this->id,
            'title' => $this->title,
            'meta'  => $this->meta ?: null,
            'root'  => $root,
        ]);

        return [
            'sdui'   => self::PROTOCOL_VERSION,
            'screen' => $screen,
        ];
    }

    public function render(int $status = 200, array $headers = []): JsonResponse
    {
        return response()->json(
            data:    $this->toArray(),
            status:  $status,
            headers: array_merge(['X-SDUI-Version' => self::PROTOCOL_VERSION], $headers),
        );
    }

    // ── Internal ───────────────────────────────────────────────────────────

    private function buildRoot(): array
    {
        if (empty($this->children)) {
            throw new SDUIException("Screen '{$this->id}' has no components.");
        }

        // Single child — use it directly as root
        if (count($this->children) === 1) {
            return $this->children[0]->toArray();
        }

        // Multiple children — wrap in a vertical stack
        return Stack::vertical(16)
            ->add($this->children)
            ->toArray();
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