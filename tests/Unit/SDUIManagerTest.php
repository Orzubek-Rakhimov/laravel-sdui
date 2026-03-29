<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SDUI\SDUIManager;
use SDUI\Screen;
use SDUI\Components\Text;
use SDUI\Components\Button;

class SDUIManagerTest extends TestCase
{
    private SDUIManager $manager;

    protected function setUp(): void
    {
        $this->manager = new SDUIManager();
    }

    public function test_manager_creation()
    {
        $this->assertInstanceOf(SDUIManager::class, $this->manager);
    }

    public function test_manager_screen_method_returns_screen()
    {
        $screen = $this->manager->screen('test-screen');
        
        $this->assertInstanceOf(Screen::class, $screen);
    }

    public function test_manager_screen_with_different_ids()
    {
        $screen1 = $this->manager->screen('screen-1');
        $screen2 = $this->manager->screen('screen-2');
        
        $this->assertNotEquals($screen1, $screen2);
    }

    public function test_manager_serialize_component()
    {
        $component = Text::make('Sample Text');
        $serialized = $this->manager->serialize($component);
        
        $this->assertIsArray($serialized);
        $this->assertEquals('text', $serialized['type']);
        $this->assertEquals('Sample Text', $serialized['props']['text']);
    }

    public function test_manager_serialize_button()
    {
        $component = Button::make('Click Me')
            ->variant('primary')
            ->disabled();
        
        $serialized = $this->manager->serialize($component);
        
        $this->assertEquals('button', $serialized['type']);
        $this->assertEquals('Click Me', $serialized['props']['label']);
        $this->assertEquals('primary', $serialized['props']['variant']);
        $this->assertTrue($serialized['props']['disabled']);
    }

    public function test_manager_serialize_multiple_components()
    {
        $components = [
            Text::make('Hello'),
            Button::make('Submit'),
            Text::make('World'),
        ];

        foreach ($components as $component) {
            $serialized = $this->manager->serialize($component);
            $this->assertIsArray($serialized);
            $this->assertArrayHasKey('type', $serialized);
        }
    }

    public function test_manager_screen_building_workflow()
    {
        $screen = $this->manager->screen('dashboard')
            ->title('Dashboard')
            ->meta('user_id', 42)
            ->add(Text::make('Welcome'))
            ->add(Button::make('Start'));

        $array = $screen->toArray();
        
        $this->assertEquals('dashboard', $array['screen']['id']);
        $this->assertEquals('Dashboard', $array['screen']['title']);
        $this->assertEquals(42, $array['screen']['meta']['user_id']);
    }

    public function test_manager_with_fluent_interface()
    {
        $result = $this->manager
            ->screen('home')
            ->add(
                Text::make('Hello World')
                    ->bold()
                    ->variant('heading')
            )
            ->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('sdui', $result);
        $this->assertArrayHasKey('screen', $result);
    }
}
