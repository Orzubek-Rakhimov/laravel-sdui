<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SDUI\Screen;
use SDUI\Components\Text;
use SDUI\Components\Button;
use SDUI\Components\Stack;
use SDUI\Exceptions\SDUIException;

class ScreenTest extends TestCase
{
    public function test_screen_creation_with_make()
    {
        $screen = Screen::make('home');
        $this->assertInstanceOf(Screen::class, $screen);
    }

    public function test_screen_with_title()
    {
        $screen = Screen::make('home')
            ->title('Welcome')
            ->add(Text::make('Content'));

        $array = $screen->toArray();
        $this->assertEquals('Welcome', $array['screen']['title']);
    }

    public function test_screen_with_meta_data()
    {
        $screen = Screen::make('home')
            ->meta('version', '1.0')
            ->meta('author', 'John Doe')
            ->add(Text::make('Content'));

        $array = $screen->toArray();
        $this->assertEquals('1.0', $array['screen']['meta']['version']);
        $this->assertEquals('John Doe', $array['screen']['meta']['author']);
    }

    public function test_screen_with_single_component()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Hello World'));

        $array = $screen->toArray();
        $this->assertNotEmpty($array['screen']['root']);
        $this->assertEquals('text', $array['screen']['root']['type']);
        $this->assertEquals('Hello World', $array['screen']['root']['props']['text']);
    }

    public function test_screen_with_multiple_components_wraps_in_stack()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Line 1'))
            ->add(Text::make('Line 2'));

        $array = $screen->toArray();
        $this->assertEquals('stack', $array['screen']['root']['type']);
        $this->assertCount(2, $array['screen']['root']['children']);
    }

    public function test_screen_add_array_of_components()
    {
        $components = [
            Text::make('First'),
            Button::make('Click me'),
        ];

        $screen = Screen::make('home')
            ->add($components);

        $array = $screen->toArray();
        $this->assertEquals('stack', $array['screen']['root']['type']);
        $this->assertCount(2, $array['screen']['root']['children']);
    }

    public function test_screen_add_if_true()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Base'))
            ->addIf(true, Text::make('Conditional'));

        $array = $screen->toArray();
        $this->assertCount(2, $array['screen']['root']['children']);
    }

    public function test_screen_add_if_false()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Base'))
            ->addIf(false, Text::make('Conditional'));

        $array = $screen->toArray();
        // Single component is used as root directly, no children array
        $this->assertEquals('text', $array['screen']['root']['type']);
    }

    public function test_screen_add_many_with_mapper()
    {
        $items = ['Item 1', 'Item 2', 'Item 3'];
        
        $screen = Screen::make('home')
            ->addMany($items, fn($item) => Text::make($item));

        $array = $screen->toArray();
        $this->assertCount(3, $array['screen']['root']['children']);
        $this->assertEquals('Item 1', $array['screen']['root']['children'][0]['props']['text']);
        $this->assertEquals('Item 2', $array['screen']['root']['children'][1]['props']['text']);
        $this->assertEquals('Item 3', $array['screen']['root']['children'][2]['props']['text']);
    }

    public function test_screen_throws_exception_when_no_components()
    {
        $this->expectException(SDUIException::class);
        Screen::make('home')->toArray();
    }

    public function test_screen_to_array_includes_protocol_version()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Test'));

        $array = $screen->toArray();
        $this->assertEquals(1, $array['sdui']);
    }

    public function test_screen_to_array_structure()
    {
        $screen = Screen::make('test-screen')
            ->title('Test Title')
            ->add(Text::make('Content'));

        $array = $screen->toArray();
        
        $this->assertArrayHasKey('sdui', $array);
        $this->assertArrayHasKey('screen', $array);
        $this->assertArrayHasKey('id', $array['screen']);
        $this->assertArrayHasKey('title', $array['screen']);
        $this->assertArrayHasKey('root', $array['screen']);
        
        $this->assertEquals('test-screen', $array['screen']['id']);
    }

    public function test_screen_render_returns_json_response()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Test'));

        $response = $screen->render();
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('application/json', $response->headers->get('content-type'));
    }

    public function test_screen_render_with_custom_status()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Test'));

        $response = $screen->render(201);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_screen_render_with_custom_headers()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Test'));

        $response = $screen->render(200, ['X-Custom-Header' => 'value']);
        $this->assertEquals('value', $response->headers->get('X-Custom-Header'));
    }

    public function test_screen_request_invalid_component()
    {
        $this->expectException(\TypeError::class);
        
        Screen::make('home')->add('not a component');
    }

    public function test_screen_meta_is_null_when_not_set()
    {
        $screen = Screen::make('home')
            ->add(Text::make('Test'));

        $array = $screen->toArray();
        $this->assertNull($array['screen']['meta']);
    }

    public function test_screen_chaining_works_correctly()
    {
        $screen = Screen::make('home')
            ->title('My Page')
            ->meta('lang', 'en')
            ->add(Text::make('Hello'))
            ->addIf(true, Button::make('Click'))
            ->addMany(['a', 'b'], fn($x) => Text::make($x));

        $array = $screen->toArray();
        $this->assertEquals('My Page', $array['screen']['title']);
        $this->assertCount(4, $array['screen']['root']['children']);
    }
}
