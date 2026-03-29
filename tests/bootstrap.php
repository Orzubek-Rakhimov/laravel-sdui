<?php

declare(strict_types=1);

// Register the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Manually load the Components file since they're all in one file
// This is needed until Components are split into individual files or composer dump-autoload is run
require __DIR__ . '/../src/Components/Components.php';

// Mock class for JsonResponse
if (!class_exists('Illuminate\Http\JsonResponse')) {
    // Create a mock JsonResponse for testing
    class MockJsonResponse {
        public $headers;
        
        public function __construct(
            public $data = null,
            private int $statusCode = 200,
            array $headerData = []
        ) {
            $this->headers = new class($headerData) {
                private array $values = [];

                public function __construct(array $values = [])
                {
                    $this->values = array_change_key_case($values, CASE_LOWER);
                }

                public function get(string $key, $default = null)
                {
                    $key = strtolower($key);
                    return $this->values[$key] ?? $default;
                }
            };
        }

        public function getStatusCode(): int
        {
            return $this->statusCode;
        }

        public function getContent(): string
        {
            return json_encode($this->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
}

// Mock Laravel's response() helper function
if (!function_exists('response')) {
    function response()
    {
        return new class {
            public function json(
                mixed $data = null,
                int $status = 200,
                array $headers = [],
                int $options = 0,
                bool $escape = false
            ) {
                // Add default content-type header
                $headers['content-type'] = $headers['content-type'] ?? 'application/json';
                
                // Return a mock JsonResponse that can pass type hints
                return new MockJsonResponse($data, $status, $headers);
            }
        };
    }
}

// Create an alias for Illuminate\Http\JsonResponse to our mock
if (!class_exists('Illuminate\Http\JsonResponse')) {
    class_alias('MockJsonResponse', 'Illuminate\Http\JsonResponse');
}


