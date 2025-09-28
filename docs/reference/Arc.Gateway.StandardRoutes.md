# StandardRoutes Class Reference

Namespace: `ARC\Gateway`

Manages registration and tracking of standard REST API routes for collections.

## Properties

- `registeredRoutes` — `array`  
  Stores all registered endpoints, keyed by collection name.

## Methods

### `__construct()`
Registers hooks for REST API init and collection registration/unregistration.

### `registerRoutes()`
Registers all stored routes with WordPress REST API.

### `onCollectionRegistered($alias, $modelClass, $config)`
Handles registration of a collection and its standard routes.

### `onCollectionUnregistered($identifier)`
Handles unregistration of a collection and removes its routes.

### `registerStandardRoutesForCollection(Collection $collection, $collectionName)`
Creates and stores standard CRUD routes for a collection.

### `unregisterStandardRoutesForCollection($collectionName)`
Removes stored routes for a collection.

### `normalizeCollectionName($modelClass)`
Converts model class to snake_case plural collection name.

### `getRegisteredRoutes()`
Returns all registered routes as an array.

### `getRoutesForCollection($collectionName)`
Returns endpoints for a specific collection.

### `hasRoutesForCollection($collectionName)`
Checks if a collection has registered routes.

### `getRouteInfo()`
Returns structured info for all registered routes including method, type, and description.

### `getRouteDescription($endpoint)`
Returns a short description of a route based on its method and type.

## Route Types

- `get_many` — `GET /collection` (all items)  
- `get_one`  — `GET /collection/{id}` (single item)  
- `create`   — `POST /collection`  
- `update`   — `PUT /collection/{id}`  
- `delete`   — `DELETE /collection/{id}`  
- `custom`   — custom routes added by developers
