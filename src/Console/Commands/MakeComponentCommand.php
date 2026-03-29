<?php

declare(strict_types=1);

namespace SDUI\Console\Commands;

use Illuminate\Console\Command;

final class MakeComponentCommand extends Command
{
    protected $signature   = 'sdui:make {name : Component class name}';
    protected $description = 'Scaffold a new SDUI component';

    public function handle(): int
    {
        $name = $this->argument('name');
        $path = app_path("SDUI/Components/{$name}.php");

        if (file_exists($path)) {
            $this->error("Component [{$name}] already exists!");
            return self::FAILURE;
        }

        $namespace = 'App\\SDUI\\Components';
        $stub      = $this->stub($namespace, $name);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $stub);

        $this->info("Component [{$name}] created at [{$path}].");
        $this->line('');
        $this->line("Register it in your React Native app:");
        $this->line("  <comment>SDUI.register('{$this->toSnake($name)}', Your{$name}Component);</comment>");

        return self::SUCCESS;
    }

    private function stub(string $namespace, string $name): string
    {
        $type = $this->toSnake($name);

        return <<<PHP
        <?php

        declare(strict_types=1);

        namespace {$namespace};

        use SDUI\Components\Component;

        final class {$name} extends Component
        {
            private function __construct()
            {
                // set default props here
            }

            public static function make(): self
            {
                return new self();
            }

            protected function type(): string
            {
                return '{$type}';
            }

            // Add fluent methods below:
            // public function someOption(string \$value): self
            // {
            //     return \$this->prop('someOption', \$value);
            // }
        }
        PHP;
    }

    private function toSnake(string $name): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
    }
}