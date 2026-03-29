<?php

declare(strict_types=1);

namespace SDUI;

use SDUI\Components\Component;
use SDUI\Contracts\Componentable;

final class SDUIManager
{
    /**
     * Start building a new screen.
     */
    public function screen(string $id): Screen
    {
        return Screen::make($id);
    }

    /**
     * Serialize any component to its array representation.
     * Useful for testing or debugging.
     */
    public function serialize(Componentable $component): array
    {
        return $component->toArray();
    }
}