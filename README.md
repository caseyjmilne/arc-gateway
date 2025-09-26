# ARC Gateway Plugin

A WordPress plugin that provides a registry system for Laravel Eloquent model collections, enabling developers to register and manage collections with extended functionality through an intuitive API.

## Features

- Register Eloquent model collections with custom configurations
- Extended query capabilities (search, filter, sort)
- Relationship loading support
- Caching configuration
- Alias support for easy collection access
- Developer-friendly fluent API

## Usage

### Registering a Collection

```php
use ARC\Gateway\Collection;

// Basic registration
Collection::register('App\Models\User');

// With configuration and alias
Collection::register('App\Models\User', [
    'cache_enabled' => true,
    'cache_duration' => 3600,
    'searchable' => ['name', 'email'],
    'sortable' => ['name', 'created_at'],
    'filters' => ['status', 'role'],
    'relations' => ['profile', 'posts']
], 'users');
```

### Using Collections

```php
use ARC\Gateway\Collection;

// Get collection instance
$userCollection = Collection::get('users');
// Or using helper function
$userCollection = arc_collection('users');

// Query methods
$allUsers = $userCollection->all();
$user = $userCollection->find(1);
$activeUsers = $userCollection->where('status', 'active')->get();

// Search functionality
$searchResults = $userCollection->search('john', ['name', 'email']);

// Filter and sort
$filtered = $userCollection->filter(['status' => 'active']);
$sorted = $userCollection->sort('created_at', 'desc');

// With relationships
$usersWithPosts = $userCollection->withRelations(['posts'])->all();
```

### Helper Functions

```php
// Register collection
arc_register_collection('App\Models\Product', [
    'searchable' => ['name', 'description'],
    'sortable' => ['name', 'price', 'created_at']
], 'products');

// Access collection
$products = arc_collection('products')->all();
$product = arc_get_collection('products')->find(1);

// Direct query
$results = arc_query('products')->where('status', 'active')->get();
```

### REST API Integration (Coming Soon)

Collections automatically expose REST endpoints:

```
GET    /wp-json/arc/v1/products
GET    /wp-json/arc/v1/products/{id}
POST   /wp-json/arc/v1/products
PUT    /wp-json/arc/v1/products/{id}
DELETE /wp-json/arc/v1/products/{id}
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `cache_enabled` | bool | false | Enable/disable caching |
| `cache_duration` | int | 3600 | Cache duration in seconds |
| `soft_deletes` | bool | false | Enable soft deletes |
| `timestamps` | bool | true | Enable timestamps |
| `relations` | array | [] | Default relationships to load |
| `scopes` | array | [] | Default model scopes to apply |
| `filters` | array | [] | Allowed filter fields |
| `sortable` | array | [] | Allowed sort fields |
| `searchable` | array | [] | Searchable fields |

## Example: Complete Setup

```php
use ARC\Gateway\Collection;

// Register User collection
Collection::register('App\Models\User', [
    'cache_enabled' => true,
    'cache_duration' => 1800,
    'searchable' => ['name', 'email', 'username'],
    'sortable' => ['name', 'email', 'created_at', 'updated_at'],
    'filters' => ['status', 'role', 'verified'],
    'relations' => ['profile', 'posts', 'comments'],
    'scopes' => ['active']
], 'users');

// Use the collection
$users = arc_collection('users')
    ->filter(['status' => 'active'])
    ->sort('created_at', 'desc')
    ->withRelations(['profile'])
    ->get();

// Search users
$results = arc_collection('users')
    ->search('john', ['name', 'email'])
    ->get();
```

## Requirements

- WordPress 5.0+
- Laravel Eloquent (via existing plugin integration)
- PHP 7.4+

## Installation

1. Install via Composer or download the plugin
2. Activate the plugin in WordPress
3. Register your collections in your theme or plugin

## Part of the ARC Framework

ARC Gateway works seamlessly with:
- **ARC Forge** - Model definitions
- **ARC Blueprint** - Field management
- **ARC Sentinel** - Authentication & authorization

Together, they provide a complete rapid development framework for WordPress.