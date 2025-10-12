# ARC Gateway Admin API Routes

This document describes the API routes created specifically for the ARC Gateway admin application. These are distinct from the auto-generated collection routes.

## Admin Routes

### GET /wp-json/arc-gateway/v1/admin-data

**Purpose:** Provides data for the admin dashboard interface.

**Authentication:** Requires `manage_options` capability (WordPress administrator).

**Headers:**
```
X-WP-Nonce: {wp_rest_nonce}
```

**Response Structure:**
```json
{
  "collections": [
    {
      "alias": "string",
      "class": "string"
    }
  ],
  "routes": {
    "collection_name": [
      {
        "type": "string",
        "method": "string",
        "route": "string"
      }
    ]
  }
}
```

## Available Data Fields

### Collections Object
Each collection in the `collections` array contains:

| Field | Type | Description |
|-------|------|-------------|
| `alias` | string | The registered alias/name for the collection |
| `class` | string | The fully qualified class name of the collection |

**Example:**
```json
{
  "alias": "posts",
  "class": "App\\Models\\Post"
}
```

### Routes Object
The `routes` object is keyed by collection name, with each containing an array of endpoint objects:

| Field | Type | Description |
|-------|------|-------------|
| `type` | string | The route type (e.g., "get_many", "get_one", "create", "update", "delete") |
| `method` | string | HTTP method (GET, POST, PUT, DELETE) |
| `route` | string | Full REST API route path |

**Example:**
```json
{
  "posts": [
    {
      "type": "get_many",
      "method": "GET",
      "route": "/wp-json/arc-gateway/v1/posts"
    },
    {
      "type": "create",
      "method": "POST",
      "route": "/wp-json/arc-gateway/v1/posts"
    },
    {
      "type": "get_one",
      "method": "GET",
      "route": "/wp-json/arc-gateway/v1/posts/{id}"
    },
    {
      "type": "update",
      "method": "PUT",
      "route": "/wp-json/arc-gateway/v1/posts/{id}"
    },
    {
      "type": "delete",
      "method": "DELETE",
      "route": "/wp-json/arc-gateway/v1/posts/{id}"
    }
  ]
}
```

## Current Usage in Admin App

The admin application currently displays:

1. **Dashboard Page (Left Column):** Lists all registered routes grouped by collection name
2. **Dashboard Page (Right Column):** Lists all registered collections with their aliases and class names
3. **Collections Page:** Displays only the collections list

## Potential Additional Data to Display

Based on the current API structure, here are suggestions for additional data that could be shown in the admin app:

### 1. Route Details
- **Description**: Each route already has a description generated (from `getRouteDescription()` in StandardRoutes.php)
- **Namespace**: Show the full namespace for each route
- **Permission Requirements**: Display authentication/permission requirements for each endpoint

### 2. Collection Details
- **Model Properties**: Show available fields/attributes from the Eloquent model
- **Relationships**: Display model relationships if available
- **Route Count**: Number of endpoints generated for each collection
- **Configuration**: Show any custom configuration passed during registration

### 3. System Information
- **Plugin Version**: Display ARC Gateway version
- **Total Collections**: Count of registered collections
- **Total Routes**: Count of all registered endpoints
- **WordPress REST API Status**: Verify REST API is accessible

### 4. Activity/Logs
- **Registration Events**: Show when collections were registered/unregistered
- **Route Usage Statistics**: If implemented, show API endpoint usage metrics
- **Recent Errors**: Display any collection or route registration errors

### 5. Testing Interface
- **Endpoint Tester**: Allow testing endpoints directly from the admin interface
- **Sample Requests**: Show example cURL/fetch commands for each endpoint
- **Response Preview**: Display sample responses for each endpoint type

## Implementation Notes

**File Location:** `includes/Endpoints/AdminDataRoute.php`

**Data Sources:**
- Collections: Retrieved via `Plugin::getInstance()->getRegistry()->getAll()`
- Routes: Retrieved via `Plugin::getInstance()->getStandardRoutes()->getRouteInfo()`

**Security:** All admin API routes require WordPress administrator privileges (`manage_options` capability) and nonce verification.
