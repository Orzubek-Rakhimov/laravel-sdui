<?php

declare(strict_types=1);

namespace SDUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

final class PreviewCommand extends Command
{
    protected $signature   = 'sdui:preview {route : API route to preview, e.g. /api/screens/home}';
    protected $description = 'Preview the JSON output for an SDUI screen route';

    public function handle(Router $router): int
    {
        $route = $this->argument('route');

        try {
            $request  = Request::create($route, 'GET');
            $response = $router->dispatch($request);
            $json     = json_encode(
                json_decode($response->getContent()),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

            $this->line('');
            $this->line("<comment>SDUI preview for:</comment> <info>{$route}</info>");
            $this->line(str_repeat('─', 60));
            $this->line($json);
            $this->line('');

        } catch (\Throwable $e) {
            $this->error("Could not preview route [{$route}]: {$e->getMessage()}");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}