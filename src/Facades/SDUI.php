<?php

declare(strict_types=1);

namespace SDUI\Facades;

use Illuminate\Support\Facades\Facade;
use SDUI\Screen;
use SDUI\SDUIManager;

/**
 * @method static Screen screen(string $id)
 * @method static array  serialize(\SDUI\Contracts\Componentable $component)
 *
 * @see SDUIManager
 */
final class SDUI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SDUIManager::class;
    }
}