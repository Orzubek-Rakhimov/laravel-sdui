<?php

declare(strict_types=1);

namespace Tests\Unit\Components;

use PHPUnit\Framework\TestCase;
use SDUI\Components\Text;
use SDUI\Components\Button;
use SDUI\Components\Image;
use SDUI\Components\Stack;
use SDUI\Actions\Action;

class ComponentsTest extends TestCase
{
    public function test_text_component_creation()
    {
        $text = Text::make('Hello World');
        $array = $text->toArray();
        
        $this->assertEquals('text', $array['type']);
        $this->assertEquals('Hello World', $array['props']['text']);
    }

    public function test_text_component_with_variant()
    {
        $text = Text::make('Title')
            ->variant('heading');
        
        $array = $text->toArray();
        $this->assertEquals('heading', $array['props']['variant']);
    }

    public function test_text_component_with_multiple_properties()
    {
        $text = Text::make('Important')
            ->bold()
            ->variant('body')
            ->color('red')
            ->align('center');
        
        $array = $text->toArray();
        $this->assertTrue($array['props']['bold']);
        $this->assertEquals('body', $array['props']['variant']);
        $this->assertEquals('red', $array['props']['color']);
        $this->assertEquals('center', $array['props']['align']);
    }

    public function test_text_component_muted()
    {
        $text = Text::make('Muted text')->muted();
        $array = $text->toArray();
        
        $this->assertTrue($array['props']['muted']);
    }

    public function test_button_component_creation()
    {
        $button = Button::make('Click Me');
        $array = $button->toArray();
        
        $this->assertEquals('button', $array['type']);
        $this->assertEquals('Click Me', $array['props']['label']);
    }

    public function test_button_component_with_variant()
    {
        $button = Button::make('Submit')
            ->variant('primary');
        
        $array = $button->toArray();
        $this->assertEquals('primary', $array['props']['variant']);
    }

    public function test_button_component_destructive()
    {
        $button = Button::make('Delete')
            ->destructive();
        
        $array = $button->toArray();
        $this->assertEquals('danger', $array['props']['variant']);
    }

    public function test_button_component_disabled()
    {
        $button = Button::make('Disabled')
            ->disabled();
        
        $array = $button->toArray();
        $this->assertTrue($array['props']['disabled']);
    }

    public function test_button_component_loading()
    {
        $button = Button::make('Loading')
            ->loading();
        
        $array = $button->toArray();
        $this->assertTrue($array['props']['loading']);
    }

    public function test_button_component_with_icon()
    {
        $button = Button::make('Download')
            ->icon('download', 'left');
        
        $array = $button->toArray();
        $this->assertEquals('download', $array['props']['icon']);
        $this->assertEquals('left', $array['props']['iconPosition']);
    }

    public function test_button_component_full_width()
    {
        $button = Button::make('Full Width')
            ->fullWidth();
        
        $array = $button->toArray();
        $this->assertTrue($array['props']['fullWidth']);
    }

    public function test_image_component_creation()
    {
        $image = Image::make('https://example.com/image.jpg');
        $array = $image->toArray();
        
        $this->assertEquals('image', $array['type']);
        $this->assertEquals('https://example.com/image.jpg', $array['props']['src']);
    }

    public function test_image_component_with_dimensions()
    {
        $image = Image::make('image.jpg')
            ->width(200)
            ->height(150);
        
        $array = $image->toArray();
        $this->assertEquals(200, $array['props']['width']);
        $this->assertEquals(150, $array['props']['height']);
    }

    public function test_image_component_rounded()
    {
        $image = Image::make('image.jpg')
            ->rounded(12);
        
        $array = $image->toArray();
        $this->assertEquals(12, $array['props']['rounded']);
    }

    public function test_image_component_circle()
    {
        $image = Image::make('avatar.jpg')
            ->circle();
        
        $array = $image->toArray();
        $this->assertTrue($array['props']['circle']);
    }

    public function test_component_with_action_navigate()
    {
        $button = Button::make('Go Home')
            ->navigateTo('home', ['id' => 1]);
        
        $array = $button->toArray();
        $this->assertNotNull($array['action']);
        $this->assertEquals('navigate', $array['action']['type']);
        $this->assertEquals('home', $array['action']['payload']['route']);
        $this->assertEquals(['id' => 1], $array['action']['payload']['params']);
    }

    public function test_component_with_action_open_url()
    {
        $button = Button::make('Open External')
            ->openUrl('https://example.com', true);
        
        $array = $button->toArray();
        $this->assertEquals('open_url', $array['action']['type']);
        $this->assertEquals('https://example.com', $array['action']['payload']['url']);
        $this->assertTrue($array['action']['payload']['inApp']);
    }

    public function test_component_with_action_emit()
    {
        $button = Button::make('Send Event')
            ->emit('item_clicked', ['itemId' => 42]);
        
        $array = $button->toArray();
        $this->assertEquals('emit', $array['action']['type']);
        $this->assertEquals('item_clicked', $array['action']['payload']['event']);
        $this->assertEquals(['itemId' => 42], $array['action']['payload']['data']);
    }

    public function test_component_with_custom_action()
    {
        $button = Button::make('Custom')
            ->action('custom_action', ['key' => 'value']);
        
        $array = $button->toArray();
        $this->assertEquals('custom_action', $array['action']['type']);
        $this->assertEquals(['key' => 'value'], $array['action']['payload']);
    }

    public function test_stack_vertical()
    {
        $stack = Stack::vertical(12)
            ->add(Text::make('Item 1'))
            ->add(Text::make('Item 2'));
        
        $array = $stack->toArray();
        $this->assertEquals('stack', $array['type']);
        $this->assertEquals('vertical', $array['props']['direction']);
        $this->assertEquals(12, $array['props']['gap']);
        $this->assertCount(2, $array['children']);
    }

    public function test_stack_horizontal()
    {
        $stack = Stack::horizontal(8)
            ->add(Text::make('Left'))
            ->add(Text::make('Right'));
        
        $array = $stack->toArray();
        $this->assertEquals('stack', $array['type']);
        $this->assertEquals('horizontal', $array['props']['direction']);
        $this->assertEquals(8, $array['props']['gap']);
    }

    public function test_component_children()
    {
        $button = Button::make('Parent');
        // Note: Components typically don't have children, but testing chaining
        $this->assertInstanceOf(Button::class, $button);
    }

    public function test_multiple_components_chaining()
    {
        $text = Text::make('Hello')
            ->bold()
            ->variant('heading')
            ->align('center')
            ->color('blue')
            ->muted();
        
        $array = $text->toArray();
        $this->assertTrue($array['props']['bold']);
        $this->assertEquals('heading', $array['props']['variant']);
        $this->assertEquals('center', $array['props']['align']);
        $this->assertEquals('blue', $array['props']['color']);
        $this->assertTrue($array['props']['muted']);
    }
}
