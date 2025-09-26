<?php
/**
 * Example usage of ARC Gateway with Standard Routes
 *
 * This file demonstrates how to register collections and automatically
 * generate standard API routes for them.
 */

// This would typically be in your theme's functions.php or another plugin

// Example: Register a collection for a User model with alias 'users'
add_action('arc_gateway_loaded', function() {
    // Example collection registration
    // Note: In real usage, you'd have actual Eloquent models

    /*
    // Register a User collection
    arc_register_collection('App\\Models\\User', [
        'searchable' => ['name', 'email'],
        'sortable' => ['name', 'email', 'created_at'],
        'filters' => ['status', 'role'],
    ], 'users');

    // Register a Post collection
    arc_register_collection('App\\Models\\Post', [
        'searchable' => ['title', 'content'],
        'sortable' => ['title', 'created_at', 'updated_at'],
        'filters' => ['status', 'category_id'],
        'relations' => ['author', 'categories'],
    ], 'posts');

    // Register a Product collection
    arc_register_collection('App\\Models\\Product', [
        'searchable' => ['name', 'description', 'sku'],
        'sortable' => ['name', 'price', 'created_at'],
        'filters' => ['category_id', 'status', 'featured'],
    ], 'products');
    */

    // For demonstration, let's show what routes would be available
    echo "<!-- ARC Gateway Standard Routes would be available at:\n";

    echo "Users Collection:\n";
    echo "GET    /wp-json/arc-gateway/v1/users     - Get all users\n";
    echo "POST   /wp-json/arc-gateway/v1/users     - Create a new user\n";
    echo "GET    /wp-json/arc-gateway/v1/users/1   - Get user by ID\n";
    echo "PUT    /wp-json/arc-gateway/v1/users/1   - Update user by ID\n";
    echo "DELETE /wp-json/arc-gateway/v1/users/1   - Delete user by ID\n\n";

    echo "Posts Collection:\n";
    echo "GET    /wp-json/arc-gateway/v1/posts     - Get all posts\n";
    echo "POST   /wp-json/arc-gateway/v1/posts     - Create a new post\n";
    echo "GET    /wp-json/arc-gateway/v1/posts/1   - Get post by ID\n";
    echo "PUT    /wp-json/arc-gateway/v1/posts/1   - Update post by ID\n";
    echo "DELETE /wp-json/arc-gateway/v1/posts/1   - Delete post by ID\n\n";

    echo "Products Collection:\n";
    echo "GET    /wp-json/arc-gateway/v1/products     - Get all products\n";
    echo "POST   /wp-json/arc-gateway/v1/products     - Create a new product\n";
    echo "GET    /wp-json/arc-gateway/v1/products/1   - Get product by ID\n";
    echo "PUT    /wp-json/arc-gateway/v1/products/1   - Update product by ID\n";
    echo "DELETE /wp-json/arc-gateway/v1/products/1   - Delete product by ID\n\n";

    echo "Query parameters supported on GET /collection:\n";
    echo "- page: Page number (default: 1)\n";
    echo "- per_page: Items per page (default: 10, max: 100)\n";
    echo "- search: Search term\n";
    echo "- order_by: Column to sort by\n";
    echo "- order: Sort direction (asc|desc)\n\n";

    echo "All routes currently return mock JSON responses until auth plugin is implemented.\n";
    echo "-->\n";
});

/*
 * Example API responses:
 *
 * GET /wp-json/arc-gateway/v1/users
 * {
 *   "success": true,
 *   "data": {
 *     "operation": "get_many",
 *     "collection": "users",
 *     "timestamp": "2025-09-25 20:15:30",
 *     "mock": true,
 *     "input": {
 *       "page": 1,
 *       "per_page": 10
 *     },
 *     "result": {
 *       "items": [
 *         {
 *           "id": 1,
 *           "title": "Sample Users 1",
 *           "status": "active",
 *           "created_at": "2025-09-25 20:15:30",
 *           "updated_at": "2025-09-25 20:15:30"
 *         }
 *       ],
 *       "total": 2,
 *       "page": 1,
 *       "per_page": 10
 *     }
 *   }
 * }
 *
 * POST /wp-json/arc-gateway/v1/users
 * {
 *   "success": true,
 *   "data": {
 *     "operation": "create",
 *     "collection": "users",
 *     "timestamp": "2025-09-25 20:15:30",
 *     "mock": true,
 *     "input": {
 *       "name": "John Doe",
 *       "email": "john@example.com"
 *     },
 *     "result": {
 *       "id": 123,
 *       "name": "John Doe",
 *       "email": "john@example.com",
 *       "created_at": "2025-09-25 20:15:30",
 *       "updated_at": "2025-09-25 20:15:30"
 *     }
 *   }
 * }
 */