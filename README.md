# Laravel SDUI - Server-Driven UI for Laravel

Build dynamic, native-like mobile and web interfaces using Laravel. Define your UI declaratively in PHP and render it anywhere.

[![PHP](https://img.shields.io/badge/PHP-8.3%2B-blue)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12%2F13-red)](https://laravel.com)
[![Tests](https://img.shields.io/badge/Tests-76%20passing-green)](#testing)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

## Features

✨ **Declarative UI Definition** - Define screens in pure PHP using a fluent, chainable API
🎯 **Type-Safe** - Full PHP 8.3+ type hints and strict typing throughout
🧩 **Component-Based** - Rich set of built-in components (Text, Button, Image, Stack, Card, Badge, etc.)
⚡ **Action System** - Handle navigation, URL opening, events, and custom actions
🔄 **Dynamic Rendering** - Conditionally render components, map collections, nest layouts
🎨 **Highly Extensible** - Create custom components by extending the base Component class
🧪 **Thoroughly Tested** - 76+ comprehensive unit and integration tests
📦 **Single Dependency** - Only requires `illuminate/support`

## Installation

```bash
composer require sdui/laravel-sdui
```

The package is auto-discovered by Laravel. If you're not using Laravel auto-discovery, add the service provider:

```php
// config/app.php
'providers' => [
    // ...
    SDUI\SDUIServiceProvider::class,
],

'aliases' => [
    // ...
    'SDUI' => SDUI\Facades\SDUI::class,
],
```

## Quick Start

### Basic Screen

```php
use SDUI\Screen;
use SDUI\Components\Text;
use SDUI\Components\Button;

$screen = Screen::make('home')
    ->title('Welcome')
    ->add(
        Text::make('Hello, World!')
            ->bold()
            ->variant('heading')
    )
    ->add(
        Button::make('Get Started')
            ->variant('primary')
            ->navigateTo('features')
    );

return $screen->render();
```

### Using the Facade

```php
use SDUI\Facades\SDUI;

$screen = SDUI::screen('dashboard')
    ->title('Dashboard')
    ->add(Text::make('Welcome to your dashboard'));

return $screen->render();
```

### Conditional Rendering

```php
$screen = Screen::make('profile')
    ->title('User Profile')
    ->add(Text::make($user->name))
    ->add(Text::make($user->email)->muted())
    ->addIf($user->isAdmin(), Button::make('Admin Panel')->destructive())
    ->addIf($user->isPremium(), Text::make('Premium User')->color('gold'));
```

### Rendering Collections

```php
$products = Product::all();

$screen = Screen::make('products')
    ->title('Our Products')
    ->addMany($products, fn($product) =>
        Stack::vertical(12)
            ->add(Image::make($product->image)->width(200)->height(200))
            ->add(Text::make($product->name)->bold())
            ->add(Text::make("$" . $product->price)->color('green'))
            ->add(
                Button::make('View')
                    ->navigateTo('product.show', ['id' => $product->id])
            )
    );
```

### Components

#### Text
```php
Text::make('Hello')
    ->bold()
    ->variant('heading') // display | heading | subheading | body | caption | label
    ->align('center')
    ->color('blue')
    ->muted()
```

#### Button
```php
Button::make('Click Me')
    ->variant('primary') // primary | secondary | ghost | danger
    ->disabled()
    ->loading()
    ->fullWidth()
    ->icon('icon-name', 'left')
    ->navigateTo('route.name', ['id' => 1])
```

#### Image
```php
Image::make('https://example.com/image.jpg')
    ->width(300)
    ->height(300)
    ->rounded(12)
    ->circle()
    ->alt('Description')
```

#### Stack (Layouts)
```php
Stack::vertical(16)  // spacing in pixels
    ->add(Text::make('Item 1'))
    ->add(Text::make('Item 2'))
    ->align('center')
    ->justify('space-between')

Stack::horizontal(8)
    ->add(Button::make('Cancel'))
    ->add(Button::make('Confirm'))
```

#### Card
```php
Card::make()
    ->add(Text::make('Card content'))
    ->padding(16)
    ->elevated()
```

#### Badge
```php
Badge::make('NEW')
    ->color('green')
    ->variant('filled')
```

#### ListItem
```php
ListItem::make('Item Label')
    ->subtitle('Subtitle text')
    ->icon('icon-name')
    ->trailing('→')
    ->disclosure()
```

### Actions

#### Navigate
```php
Button::make('Go to Profile')
    ->navigateTo('profile.show', ['id' => $user->id])
```

#### Open URL
```php
Button::make('Visit Website')
    ->openUrl('https://example.com', $inApp = true)
```

#### Emit Events
```php
Button::make('Submit')
    ->emit('form_submitted', ['status' => 'success'])
```

#### Custom Actions
```php
Button::make('Custom')
    ->action('my_custom_action', ['key' => 'value'])
```

### Metadata

```php
$screen = Screen::make('app')
    ->title('My App')
    ->meta('version', '1.0')
    ->meta('user_id', auth()->id())
    ->meta('timestamp', now())
    ->add(/* components */);
```

## Building a REST API Response

```php
use Illuminate\Routing\Controller;

class ScreenController extends Controller
{
    public function home()
    {
        return Screen::make('home')
            ->title('Welcome')
            ->add(Text::make('This is your home screen'))
            ->render();
    }

    public function statusCode()
    {
        return Screen::make('error')
            ->add(Text::make('Something went wrong'))
            ->render(500);
    }

    public function withHeaders()
    {
        return Screen::make('app')
            ->add(Text::make('Content'))
            ->render(200, [
                'X-Custom-Header' => 'value',
            ]);
    }
}
```

## Artisan Commands

### Make a Custom Component

```bash
php artisan sdui:make-component CustomComponent
```

This creates a new component template in `app/SDUI/Components/CustomComponent.php`

### Preview a Screen

```bash
php artisan sdui:preview HomeScreen
```

## JSON Output Format

```json
{
  "sdui": 1,
  "screen": {
    "id": "home",
    "title": "Welcome",
    "meta": {
      "version": "1.0"
    },
    "root": {
      "type": "stack",
      "props": {
        "direction": "vertical",
        "gap": 16
      },
      "children": [
        {
          "type": "text",
          "props": {
            "text": "Hello World",
            "bold": true,
            "variant": "heading"
          }
        },
        {
          "type": "button",
          "props": {
            "label": "Click Me",
            "variant": "primary"
          },
          "action": {
            "type": "navigate",
            "payload": {
              "route": "features"
            }
          }
        }
      ]
    }
  }
}
```

## Testing

```bash
# Run all tests
composer test

# Run with coverage
XDEBUG_MODE=coverage composer test:coverage

# Run specific test
./vendor/bin/phpunit tests/Unit/ScreenTest.php

# Watch mode
composer test:watch
```

**Test Coverage**: 76+ tests covering all components, actions, and edge cases.

## Available Components

- **Text** - Display text with styling
- **Button** - Interactive button with actions
- **Image** - Display images with sizing
- **Stack** - Vertical/horizontal layouts
- **Card** - Contained content with styling
- **Badge** - Labels and tags
- **Avatar** - User profile pictures
- **Spacer** - Add spacing between elements
- **Divider** - Visual separators
- **ListItem** - List entry components
- **StatGrid** - Display statistics in a grid

## Creating Custom Components

```php
<?php

namespace App\SDUI\Components;

use SDUI\Components\Component;

final class CustomComponent extends Component
{
    private function __construct(string $title)
    {
        $this->props['title'] = $title;
    }

    public static function make(string $title): self
    {
        return new self($title);
    }

    protected function type(): string
    {
        return 'custom_component';
    }

    public function subtitle(string $text): self
    {
        return $this->prop('subtitle', $text);
    }
}
```

## API Reference

### Screen Methods

| Method | Description |
|--------|-------------|
| `make(string $id)` | Create a new screen |
| `title(string $title)` | Set screen title |
| `meta(string $key, mixed $value)` | Add metadata |
| `add(Componentable\|array $component)` | Add component(s) |
| `addIf(bool $condition, Componentable $component)` | Conditionally add component |
| `addMany(iterable $items, callable $mapper)` | Map collection to components |
| `toArray(): array` | Convert to array |
| `render(int $status = 200, array $headers = []): JsonResponse` | Render as JSON response |

### Component Methods

| Method | Description |
|--------|-------------|
| `make()` | Create component instance |
| `prop(string $key, mixed $value)` | Set property |
| `navigateTo(string $route, array $params = [])` | Navigate to route |
| `openUrl(string $url, bool $inApp = false)` | Open URL |
| `emit(string $event, mixed $data = null)` | Emit event |
| `action(string $type, mixed $payload = null)` | Custom action |
| `add(Componentable\|array $component)` | Add child components |
| `toArray(): array` | Convert to array |

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This library is open-sourced software licensed under the [MIT license](LICENSE).

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a detailed history of changes.

## Support

For issues, questions, or suggestions, please open an [issue on GitHub](https://github.com/Orzubek-Rakhimov/laravel-sdui/issues).
