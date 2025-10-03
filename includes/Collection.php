<?php

namespace ARC\Gateway;

/**
 * Abstract Collection class that configures API routes for Eloquent models
 * 
 * Usage:
 * class TicketCollection extends \ARC\Gateway\Collection {
 *     protected $model = \TicketSystem\TicketModel::class;
 * }
 * 
 * Then register: TicketCollection::register();
 */
abstract class Collection
{
    /**
     * @var string Eloquent model class name (must be set by child class)
     */
    protected $model;

    /**
     * @var array API route configuration
     */
    protected $routes = [
        'enabled' => true,
        'prefix' => null,           // Auto-generated from model if null
        'methods' => [
            'get_many' => true,      // GET /tickets
            'get_one' => true,       // GET /tickets/{id}
            'create' => true,        // POST /tickets
            'update' => true,        // PUT/PATCH /tickets/{id}
            'delete' => true,        // DELETE /tickets/{id}
        ],
        'middleware' => [],
        'permissions' => [],         // WP capabilities required
    ];

    /**
     * @var array Model configuration
     */
    protected $config = [
        'searchable' => [],          // Columns to search
        'filterable' => [],          // Columns that can be filtered
        'sortable' => [],            // Columns that can be sorted
        'relations' => [],           // Relations to eager load
        'hidden' => [],              // Fields to hide in API responses
        'appends' => [],             // Accessors to append in API responses
        'per_page' => 15,            // Default pagination
        'max_per_page' => 100,       // Maximum items per page
    ];

    /**
     * @var \Illuminate\Database\Eloquent\Model Model instance
     */
    private $modelInstance;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validateModel();
        
        // Auto-generate route prefix if not set
        if ($this->routes['prefix'] === null) {
            $this->routes['prefix'] = $this->generateRoutePrefix();
        }
    }

    /**
     * Register this collection with the CollectionRegistry
     * 
     * @param string|null $alias Optional alias for the collection
     * @return static
     */
    public static function register($alias = null)
    {
        $instance = new static();
        return Plugin::getInstance()->getRegistry()->register($instance, $alias);
    }

    /**
     * Validate that model property is set and valid
     */
    protected function validateModel()
    {
        if (!$this->model) {
            throw new \InvalidArgumentException(
                static::class . " must define a \$model property"
            );
        }

        if (!class_exists($this->model)) {
            throw new \InvalidArgumentException(
                "Model class {$this->model} does not exist"
            );
        }

        $reflection = new \ReflectionClass($this->model);
        if (!$reflection->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
            throw new \InvalidArgumentException(
                "{$this->model} must extend Illuminate\Database\Eloquent\Model"
            );
        }
    }

    /**
     * Generate route prefix from model name
     */
    protected function generateRoutePrefix()
    {
        $modelName = class_basename($this->model);
        
        // Convert "TicketModel" or "Ticket" to "tickets"
        $prefix = str_replace('Model', '', $modelName);
        $prefix = strtolower($prefix);
        
        // Simple pluralization (can be made more sophisticated)
        if (!str_ends_with($prefix, 's')) {
            $prefix .= 's';
        }
        
        return $prefix;
    }

    /**
     * Get fresh model instance
     */
    public function getModelInstance()
    {
        if (!$this->modelInstance) {
            $this->modelInstance = new $this->model();
        }
        return clone $this->modelInstance;
    }

    /**
     * Get model class name
     */
    public function getModelClass()
    {
        return $this->model;
    }

    /**
     * Get route configuration
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Get configuration value(s)
     */
    public function getConfig($key = null)
    {
        if ($key) {
            return $this->config[$key] ?? null;
        }
        return $this->config;
    }

    /**
     * Check if route method is enabled
     */
    public function isRouteEnabled($method)
    {
        return $this->routes['enabled'] && 
               ($this->routes['methods'][$method] ?? false);
    }

    /**
     * Get route prefix
     */
    public function getRoutePrefix()
    {
        return $this->routes['prefix'];
    }

    /**
     * Override route configuration
     */
    protected function configureRoutes(array $config)
    {
        $this->routes = array_merge($this->routes, $config);
    }

    /**
     * Override configuration
     */
    protected function configureApi(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }
}