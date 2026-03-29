# SDUI Library Tests

Comprehensive test suite for the Laravel SDUI library.

## Quick Setup

```bash
# Run tests immediately (bootstrap handles all setup)
composer test

# Run with coverage report
XDEBUG_MODE=coverage composer test:coverage

# Run specific test file
./vendor/bin/phpunit tests/Unit/ScreenTest.php

# Run with verbose output
./vendor/bin/phpunit --verbose
```

## Test Structure

```
tests/
├── bootstrap.php                   # Test bootstrap with Laravel mocks
├── README.md                       # This file
└── Unit/
    ├── ActionTest.php              # Tests for Action system (14 tests)
    ├── ScreenTest.php              # Tests for Screen class (20 tests)
    ├── SDUIManagerTest.php         # Tests for SDUIManager (8 tests)
    ├── IntegrationTest.php         # Integration tests (15 tests)
    └── Components/
        └── ComponentsTest.php      # Tests for UI components (25 tests)
```

## Test Coverage

Total: **82+ tests** covering:

### Screen Class (ScreenTest.php)
- Screen creation and builder pattern
- Adding components (single, multiple, arrays)
- Conditional rendering with `addIf()`
- Mapping collections with `addMany()`
- Title and metadata management
- Auto-wrapping multiple components in Stack
- JSON serialization via `toArray()`
- Response rendering as `JsonResponse`
- Error handling for empty/invalid components

### Components (Components/ComponentsTest.php)
- **Text**: variants, styling (bold, muted, color, alignment)
- **Button**: variants, states (disabled, loading), icons, actions
- **Image**: sizing, rounding, circle mode
- **Stack**: vertical/horizontal layouts with spacing
- **Component actions**: navigate, openUrl, emit events, custom actions

### Actions (ActionTest.php)
- Navigate to routes with parameters
- Open URLs (in-app/external)
- Emit events with data payloads
- Refresh page actions
- Custom actions with typed payloads

### SDUIManager (SDUIManagerTest.php)
- Screen creation via manager
- Component serialization
- Fluent interface workflows

### Integration Tests (IntegrationTest.php)
- Complex nested screen structures
- Conditional rendering workflows
- Dynamic list rendering
- Action chaining
- Error scenarios (empty screens, invalid components)
- Large datasets (100+ items)
- Metadata preservation

## Test Bootstrap (bootstrap.php)

The bootstrap file handles:
1. **Composer autoloader** - Loads all dependencies
2. **Components loading** - Includes Components.php since all components are defined in one file
3. **Laravel mock** - Provides `response()->json()` mock function for testing

This allows tests to run without a full Laravel application.

## Running Tests in CI/CD

```bash
# Run all tests
composer test

# With coverage (requires Xdebug)
XDEBUG_MODE=coverage composer test:coverage

# With watch mode (re-runs tests on file changes)
composer test:watch
```

## Adding New Tests

### Test File Naming
Files must end with `Test.php` and follow the same namespace structure as the code being tested:
- `src/Screen.php` → `tests/Unit/ScreenTest.php`
- `src/Components/Components.php` → `tests/Unit/Components/ComponentsTest.php`

### Test Class Template

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SDUI\YourClass;

class YourClassTest extends TestCase
{
    public function test_describes_behavior()
    {
        // Arrange
        $instance = YourClass::make();
        
        // Act
        $result = $instance->someMethod();
        
        // Assert
        $this->assertEquals('expected', $result);
    }
}
```

### Useful Assertions

```php
// Basic assertions
$this->assertEquals($expected, $actual);
$this->assertTrue($condition);
$this->assertFalse($condition);

// Type/instance checks
$this->assertIsArray($var);
$this->assertInstanceOf(ClassName::class, $obj);

// Array assertions
$this->assertArrayHasKey('key', $array);
$this->assertArrayNotHasKey('key', $array);
$this->assertCount($count, $array);

// Null checks
$this->assertNull($value);
$this->assertNotNull($value);

// Exception testing
$this->expectException(ExceptionClass::class);
$this->expectExceptionMessage('message');

// String assertions
$this->assertStringContainsString('needle', 'haystack');
```

## Test Statistics

- **Total Tests**: 82+
- **Test Classes**: 5
- **Assertions**: 200+
- **Code Coverage Target**: 80%+

## Configuration

### phpunit.xml

Located at project root. Key settings:
- **Bootstrap**: `tests/bootstrap.php` - Loads autoloader and mocks
- **Test discovery**: `tests/**/*Test.php`
- **Source coverage**: `src/` directory
- **Cache**: `.phpunit.cache/`

### composer.json

Test scripts registered:
```json
"scripts": {
    "test": "phpunit",
    "test:coverage": "phpunit --coverage-html coverage --coverage-text",
    "test:watch": "phpunit --watch"
}
```

Autoload-dev configuration:
```json
"autoload-dev": {
    "psr-4": {
        "Tests\\": "tests/"
    }
}
```

## Troubleshooting

### "Class not found" errors
The bootstrap file manually loads `src/Components/Components.php`. If this fails:
1. Verify the file exists: `ls src/Components/Components.php`
2. Check bootstrap path is correct
3. Run: `composer dump-autoload -o`

### "response() function not found"
The bootstrap provides a mock. If the mock isn't loading:
1. Verify `tests/bootstrap.php` exists
2. Check `phpunit.xml` `bootstrap` attribute points to it
3. Verify no other bootstrap is conflicting

### Coverage not generating  
Coverage requires Xdebug:
```bash
XDEBUG_MODE=coverage composer test:coverage
```

Otherwise use: `composer test` (without coverage)

