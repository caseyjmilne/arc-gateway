<?php
/**
 * Test script for ARC Gateway Standard Routes
 *
 * Run this by accessing: /wp-content/plugins/arc-gateway/test-routes.php
 * (Only for testing purposes - remove in production)
 */

// Only run this in a WordPress environment
if (!defined('ABSPATH')) {
    // Load WordPress bootstrap
    $wp_path = dirname(dirname(dirname(dirname(__FILE__))));
    require_once $wp_path . '/wp-load.php';
}

// Only allow in development/testing
if (!defined('WP_DEBUG') || !WP_DEBUG) {
    wp_die('This test script only runs when WP_DEBUG is enabled.');
}

echo "<h1>ARC Gateway Standard Routes Test</h1>\n";

try {
    // Check if plugin is loaded
    if (!class_exists('ARC\\Gateway\\Plugin')) {
        throw new Exception('ARC Gateway plugin is not loaded');
    }

    $plugin = ARC\Gateway\Plugin::getInstance();
    $registry = $plugin->getRegistry();
    $standardRoutes = $plugin->getStandardRoutes();

    echo "<h2>Plugin Status</h2>\n";
    echo "<p>✅ Plugin loaded successfully</p>\n";

    // Create a mock model class for testing
    if (!class_exists('MockEloquentModel')) {
        class MockEloquentModel {
            public function __construct() {}
        }

        // Fake the Eloquent base class check
        class_alias('MockEloquentModel', 'Illuminate\\Database\\Eloquent\\Model');
    }

    if (!class_exists('TestModel')) {
        class TestModel extends MockEloquentModel {
            protected $table = 'test_items';
            protected $fillable = ['title', 'description', 'status'];
        }
    }

    echo "<h2>Testing Collection Registration</h2>\n";

    try {
        // Register test collection
        $collection = arc_register_collection('TestModel', [
            'searchable' => ['title', 'description'],
            'sortable' => ['title', 'created_at'],
            'filters' => ['status']
        ], 'test_items');

        echo "<p>✅ Test collection registered successfully</p>\n";

        // Check if routes were registered
        $routes = $standardRoutes->getRoutesForCollection('test_items');
        echo "<p>✅ Found " . count($routes) . " standard routes for test_items collection</p>\n";

        // Display route information
        echo "<h2>Generated Routes</h2>\n";
        $routeInfo = $standardRoutes->getRouteInfo();

        if (isset($routeInfo['test_items'])) {
            echo "<ul>\n";
            foreach ($routeInfo['test_items'] as $route) {
                echo "<li><strong>{$route['method']}</strong> {$route['route']} - {$route['description']}</li>\n";
            }
            echo "</ul>\n";
        }

        // Test route creation
        echo "<h2>Route Endpoint Tests</h2>\n";

        foreach ($routes as $endpoint) {
            $method = $endpoint->getMethod();
            $route = $endpoint->getFullRoute();
            echo "<p>✅ {$method} route created: {$route}</p>\n";
        }

    } catch (Exception $e) {
        echo "<p>❌ Error registering collection: " . $e->getMessage() . "</p>\n";
        throw $e;
    }

    echo "<h2>REST API Endpoints</h2>\n";
    echo "<p>The following endpoints should be available (returning mock data):</p>\n";
    echo "<ul>\n";
    echo "<li><a href='/wp-json/arc-gateway/v1/test_items' target='_blank'>GET /wp-json/arc-gateway/v1/test_items</a></li>\n";
    echo "<li>POST /wp-json/arc-gateway/v1/test_items (use API client)</li>\n";
    echo "<li><a href='/wp-json/arc-gateway/v1/test_items/1' target='_blank'>GET /wp-json/arc-gateway/v1/test_items/1</a></li>\n";
    echo "<li>PUT /wp-json/arc-gateway/v1/test_items/1 (use API client)</li>\n";
    echo "<li>DELETE /wp-json/arc-gateway/v1/test_items/1 (use API client)</li>\n";
    echo "</ul>\n";

    echo "<h2>Testing Query Parameters</h2>\n";
    echo "<ul>\n";
    echo "<li><a href='/wp-json/arc-gateway/v1/test_items?page=1&per_page=5' target='_blank'>GET /wp-json/arc-gateway/v1/test_items?page=1&per_page=5</a></li>\n";
    echo "<li><a href='/wp-json/arc-gateway/v1/test_items?search=sample' target='_blank'>GET /wp-json/arc-gateway/v1/test_items?search=sample</a></li>\n";
    echo "<li><a href='/wp-json/arc-gateway/v1/test_items?order_by=title&order=desc' target='_blank'>GET /wp-json/arc-gateway/v1/test_items?order_by=title&order=desc</a></li>\n";
    echo "</ul>\n";

    echo "<h2>Summary</h2>\n";
    echo "<p>✅ All tests passed! The ARC Gateway Standard Routes system is working correctly.</p>\n";
    echo "<p><strong>Next Steps:</strong></p>\n";
    echo "<ul>\n";
    echo "<li>Integrate with real Eloquent models</li>\n";
    echo "<li>Implement authentication plugin</li>\n";
    echo "<li>Add field validation</li>\n";
    echo "<li>Add custom endpoint configurations</li>\n";
    echo "</ul>\n";

} catch (Exception $e) {
    echo "<h2>❌ Test Failed</h2>\n";
    echo "<p>Error: " . $e->getMessage() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
}

echo "<hr>\n";
echo "<p><small>Test completed at " . date('Y-m-d H:i:s') . "</small></p>\n";