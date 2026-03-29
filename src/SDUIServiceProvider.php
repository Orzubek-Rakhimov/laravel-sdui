<?php

declare(strict_types=1);

namespace SDUI;

use Illuminate\Support\ServiceProvider;
use SDUI\Console\Commands\MakeComponentCommand;
use SDUI\Console\Commands\PreviewCommand;

final class SDUIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SDUIManager::class, fn () => new SDUIManager());
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeComponentCommand::class,
                PreviewCommand::class,
            ]);
        }
    }
}