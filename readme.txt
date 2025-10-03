=== ARC Gateway ===
Contributors: arcwp
Tags: api, eloquent, laravel, rest-api, collections
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that provides a registry system for Laravel Eloquent model collections, enabling auto-generation of REST API routes.

== Description ==

ARC Gateway provides a registry system for Laravel Eloquent model collections, enabling developers to register and manage collections with extended functionality through an intuitive API.

= Features =

* Register Eloquent model collections with custom configurations
* Extended query capabilities (search, filter, sort)
* Relationship loading support
* Caching configuration
* Alias support for easy collection access
* Developer-friendly fluent API

= Part of the ARC Framework =

ARC Gateway works seamlessly with:

* **ARC Forge** - Model definitions
* **ARC Blueprint** - Field management
* **ARC Sentinel** - Authentication & authorization

Together, they provide a complete rapid development framework for WordPress.

== Installation ==

1. Install via Composer or download the plugin
2. Activate the plugin in WordPress
3. Register your collections in your theme or plugin

== Usage ==

= Registering a Collection =

Basic registration:

`
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
`

= Using Collections =

`
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
`

= Helper Functions =

`
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
`

= REST API Integration (Coming Soon) =

Collections automatically expose REST endpoints:

`
GET    /wp-json/arc/v1/products
GET    /wp-json/arc/v1/products/{id}
POST   /wp-json/arc/v1/products
PUT    /wp-json/arc/v1/products/{id}
DELETE /wp-json/arc/v1/products/{id}
`

= Configuration Options =

* `cache_enabled` (bool, default: false) - Enable/disable caching
* `cache_duration` (int, default: 3600) - Cache duration in seconds
* `soft_deletes` (bool, default: false) - Enable soft deletes
* `timestamps` (bool, default: true) - Enable timestamps
* `relations` (array, default: []) - Default relationships to load
* `scopes` (array, default: []) - Default model scopes to apply
* `filters` (array, default: []) - Allowed filter fields
* `sortable` (array, default: []) - Allowed sort fields
* `searchable` (array, default: []) - Searchable fields

== Frequently Asked Questions ==

= What are the requirements? =

* WordPress 5.0+
* Laravel Eloquent (via existing plugin integration)
* PHP 7.4+

= How do I register a collection? =

Use the `Collection::register()` method or the `arc_register_collection()` helper function. See the Usage section for examples.

== Changelog ==

= 1.0.0 =
* Initial release
