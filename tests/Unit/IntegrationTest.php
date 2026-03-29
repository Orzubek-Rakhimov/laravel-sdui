<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SDUI\Screen;
use SDUI\Components\Text;
use SDUI\Components\Button;
use SDUI\Components\Stack;
use SDUI\Components\Image;
use SDUI\Actions\Action;
use SDUI\Exceptions\SDUIException;

class IntegrationTest extends TestCase
{
    public function test_complex_screen_with_nested_components()
    {
        $screen = Screen::make('product')
            ->title('Product Details')
            ->add(
                Stack::vertical(16)
                    ->add(Image::make('product.jpg')->width(300)->height(300))
                    ->add(Text::make('Amazing Product')->bold()->variant('heading'))
                    ->add(Text::make('$99.99')->color('green'))
                    ->add(Button::make('Add to Cart')->variant('primary'))
            );

        $array = $screen->toArray();
        
        $this->assertNotEmpty($array['screen']['root']);
        $this->assertEquals('stack', $array['screen']['root']['type']);
    }

    public function test_screen_with_conditional_rendering()
    {
        $isAdmin = true;
        $isPremium = false;

        $screen = Screen::make('dashboard')
            ->title('User Dashboard')
            ->add(Text::make('Welcome'))
            ->addIf($isAdmin, Button::make('Admin Panel')->variant('danger'))
            ->addIf($isPremium, Text::make('Premium Badge'));

        $array = $screen->toArray();
        $this->assertCount(2, $array['screen']['root']['children']);
    }

    public function test_screen_with_mapped_list()
    {
        $items = [
            ['id' => 1, 'name' => 'Product 1'],
            ['id' => 2, 'name' => 'Product 2'],
            ['id' => 3, 'name' => 'Product 3'],
        ];

        $screen = Screen::make('products')
            ->title('Products')
            ->addMany($items, fn($item) => 
                Button::make($item['name'])
                    ->navigateTo('product.show', ['id' => $item['id']])
            );

        $array = $screen->toArray();
        $this->assertCount(3, $array['screen']['root']['children']);
    }

    public function test_button_with_multiple_actions_last_wins()
    {
        $button = Button::make('Click')
            ->navigateTo('home')
            ->openUrl('https://example.com')
            ->emit('clicked');

        $array = $button->toArray();
        $this->assertEquals('emit', $array['action']['type']);
    }

    public function test_screen_with_actions_on_components()
    {
        $screen = Screen::make('actions')
            ->title('Action Examples')
            ->add(
                Button::make('Navigate')
                    ->navigateTo('dashboard', ['tab' => 'overview'])
            )
            ->add(
                Button::make('External Link')
                    ->openUrl('https://example.com', true)
                    ->fullWidth()
            )
            ->add(
                Button::make('Send Event')
                    ->emit('form_submitted', ['status' => 'success'])
                    ->disabled()
            );

        $array = $screen->toArray();
        $children = $array['screen']['root']['children'];
        
        $this->assertEquals('navigate', $children[0]['action']['type']);
        $this->assertEquals('open_url', $children[1]['action']['type']);
        $this->assertEquals('emit', $children[2]['action']['type']);
    }

    public function test_error_when_adding_invalid_component()
    {
        $this->expectException(SDUIException::class);
        $this->expectExceptionMessage('Expected Componentable');

        Screen::make('home')->add(['invalid' => 'data']);
    }

    public function test_error_when_screen_empty()
    {
        $this->expectException(SDUIException::class);
        $this->expectExceptionMessage("Screen 'empty' has no components");

        Screen::make('empty')->toArray();
    }

    public function test_screen_json_response_headers()
    {
        $screen = Screen::make('api')
            ->add(Text::make('API Response'));

        $response = $screen->render(200, [
            'X-Custom' => 'value',
            'X-Request-Id' => '12345',
        ]);

        $this->assertEquals('value', $response->headers->get('X-Custom'));
        $this->assertEquals('12345', $response->headers->get('X-Request-Id'));
        $this->assertEquals('1', $response->headers->get('X-SDUI-Version'));
    }

    public function test_complex_nested_stack_structure()
    {
        $screen = Screen::make('complex')
            ->add(
                Stack::vertical(16)
                    ->add(
                        Stack::horizontal(8)
                            ->add(Image::make('icon.png')->width(32)->height(32))
                            ->add(Text::make('Title'))
                    )
                    ->add(Text::make('Description'))
                    ->add(
                        Stack::horizontal(8)
                            ->add(Button::make('Cancel'))
                            ->add(Button::make('Confirm')->variant('primary'))
                    )
            );

        $array = $screen->toArray();
        $this->assertEquals('stack', $array['screen']['root']['type']);
    }

    public function test_empty_array_add_no_children()
    {
        $this->expectException(SDUIException::class);
        $this->expectExceptionMessage("Screen 'home' has no components");

        Screen::make('home')->add([])->toArray();
    }

    public function test_mixed_component_types_in_stack()
    {
        $screen = Screen::make('mixed')
            ->add(
                Stack::vertical(12)
                    ->add(Text::make('Title')->bold())
                    ->add(Image::make('image.jpg'))
                    ->add(Button::make('Action')->variant('primary'))
            );

        $root = $screen->toArray()['screen']['root'];
        $this->assertCount(3, $root['children']);
        $this->assertEquals('text', $root['children'][0]['type']);
        $this->assertEquals('image', $root['children'][1]['type']);
        $this->assertEquals('button', $root['children'][2]['type']);
    }

    public function test_large_number_of_items()
    {
        $items = range(1, 100);
        
        $screen = Screen::make('large-list')
            ->addMany($items, fn($i) => Text::make("Item $i"));

        $array = $screen->toArray();
        $this->assertCount(100, $array['screen']['root']['children']);
    }

    public function test_meta_data_preservation()
    {
        $screen = Screen::make('metadata')
            ->title('Page Title')
            ->meta('version', '1.0')
            ->meta('timestamp', '2024-01-01')
            ->meta('nested', ['key' => 'value'])
            ->add(Text::make('Content'));

        $array = $screen->toArray();
        $meta = $array['screen']['meta'];
        
        $this->assertEquals('1.0', $meta['version']);
        $this->assertEquals('2024-01-01', $meta['timestamp']);
        $this->assertEquals(['key' => 'value'], $meta['nested']);
    }
}
