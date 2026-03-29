<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SDUI\Actions\Action;

class ActionTest extends TestCase
{
    public function test_action_navigate_creation()
    {
        $action = Action::navigate('home');
        $array = $action->toArray();
        
        $this->assertEquals('navigate', $array['type']);
        $this->assertEquals('home', $array['payload']['route']);
    }

    public function test_action_navigate_with_params()
    {
        $action = Action::navigate('user.profile', ['id' => 123]);
        $array = $action->toArray();
        
        $this->assertEquals('navigate', $array['type']);
        $this->assertEquals('user.profile', $array['payload']['route']);
        $this->assertEquals(['id' => 123], $array['payload']['params']);
    }

    public function test_action_navigate_without_params_omits_params_key()
    {
        $action = Action::navigate('home', []);
        $array = $action->toArray();
        
        $this->assertArrayNotHasKey('params', $array['payload']);
    }

    public function test_action_open_url()
    {
        $action = Action::openUrl('https://example.com');
        $array = $action->toArray();
        
        $this->assertEquals('open_url', $array['type']);
        $this->assertEquals('https://example.com', $array['payload']['url']);
        $this->assertFalse($array['payload']['inApp']);
    }

    public function test_action_open_url_in_app()
    {
        $action = Action::openUrl('https://example.com', true);
        $array = $action->toArray();
        
        $this->assertEquals('open_url', $array['type']);
        $this->assertEquals('https://example.com', $array['payload']['url']);
        $this->assertTrue($array['payload']['inApp']);
    }

    public function test_action_emit()
    {
        $action = Action::emit('button_clicked');
        $array = $action->toArray();
        
        $this->assertEquals('emit', $array['type']);
        $this->assertEquals('button_clicked', $array['payload']['event']);
    }

    public function test_action_emit_with_data()
    {
        $action = Action::emit('form_submitted', ['id' => 5, 'status' => 'success']);
        $array = $action->toArray();
        
        $this->assertEquals('emit', $array['type']);
        $this->assertEquals('form_submitted', $array['payload']['event']);
        $this->assertEquals(['id' => 5, 'status' => 'success'], $array['payload']['data']);
    }

    public function test_action_emit_without_data_omits_data_key()
    {
        $action = Action::emit('event_name', null);
        $array = $action->toArray();
        
        $this->assertArrayNotHasKey('data', $array['payload']);
    }

    public function test_action_refresh()
    {
        $action = Action::refresh();
        $array = $action->toArray();
        
        $this->assertEquals('refresh', $array['type']);
        // Refresh has no payload
        $this->assertNull($array['payload']);
    }

    public function test_action_custom()
    {
        $action = Action::custom('my_action', ['custom' => 'payload']);
        $array = $action->toArray();
        
        $this->assertEquals('my_action', $array['type']);
        $this->assertEquals(['custom' => 'payload'], $array['payload']);
    }

    public function test_action_custom_without_payload()
    {
        $action = Action::custom('simple_action', null);
        $array = $action->toArray();
        
        $this->assertEquals('simple_action', $array['type']);
        $this->assertNull($array['payload']);
    }

    public function test_action_is_componentable()
    {
        $action = Action::navigate('home');
        
        $this->assertTrue(method_exists($action, 'toArray'));
    }

    public function test_action_payload_types()
    {
        $action = Action::custom('test', 42);
        $array = $action->toArray();
        $this->assertEquals(42, $array['payload']);

        $action = Action::custom('test', 'string');
        $array = $action->toArray();
        $this->assertEquals('string', $array['payload']);

        $action = Action::custom('test', true);
        $array = $action->toArray();
        $this->assertTrue($array['payload']);
    }

    public function test_multiple_navigate_actions_different_routes()
    {
        $actions = [
            Action::navigate('home'),
            Action::navigate('about'),
            Action::navigate('contact'),
        ];

        foreach ($actions as $action) {
            $array = $action->toArray();
            $this->assertEquals('navigate', $array['type']);
        }
    }
}
