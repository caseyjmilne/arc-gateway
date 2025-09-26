<?php

namespace ARC\Gateway;

class Collection
{
    protected $modelClass;
    protected $config;
    protected $model;

    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        $this->config = array_merge($this->getDefaultConfig(), $config);
        $this->validateModelClass();
    }

    /**
     * Register a new collection
     * 
     * @param string $modelClass Eloquent model class name
     * @param array $config Collection configuration
     * @param string|null $alias Optional alias for the collection
     * @return Collection
     */
    public static function register($modelClass, $config = [], $alias = null)
    {
        return Plugin::getInstance()->getRegistry()->register($modelClass, $config, $alias);
    }

    /**
     * Get a registered collection
     * 
     * @param string $identifier Model class name or alias
     * @return Collection
     */
    public static function get($identifier)
    {
        return Plugin::getInstance()->getRegistry()->get($identifier);
    }

    /**
     * Check if a collection is registered
     * 
     * @param string $identifier Model class name or alias
     * @return bool
     */
    public static function has($identifier)
    {
        return Plugin::getInstance()->getRegistry()->has($identifier);
    }

    protected function getDefaultConfig()
    {
        return [
            'cache_enabled' => true,
            'cache_duration' => 3600,
            'soft_deletes' => false,
            'timestamps' => true,
            'relations' => [],
            'scopes' => [],
            'filters' => [],
            'sortable' => [],
            'searchable' => []
        ];
    }

    protected function validateModelClass()
    {
        if (!class_exists($this->modelClass)) {
            throw new \InvalidArgumentException("Model class {$this->modelClass} does not exist");
        }

        $reflection = new \ReflectionClass($this->modelClass);

        if (!$reflection->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
            throw new \InvalidArgumentException("Class {$this->modelClass} must extend Illuminate\Database\Eloquent\Model");
        }
    }

    /**
     * Get the Eloquent model instance
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = new $this->modelClass();
        }
        return $this->model;
    }

    /**
     * Get a fresh query builder
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->getModel()->newQuery();
    }

    /**
     * Get all records
     * 
     * @param array $columns Columns to select
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'])
    {
        $query = $this->query();
        $this->applyScopes($query);
        return $query->get($columns);
    }

    /**
     * Find a record by ID
     * 
     * @param mixed $id Record ID
     * @param array $columns Columns to select
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find($id, $columns = ['*'])
    {
        return $this->query()->find($id, $columns);
    }

    /**
     * Add a where clause to the query
     * 
     * @param string $column Column name
     * @param mixed $operator Operator or value
     * @param mixed $value Value (if operator is provided)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function where($column, $operator = null, $value = null)
    {
        $query = $this->query();
        $this->applyScopes($query);
        return $query->where($column, $operator, $value);
    }

    /**
     * Create a new record
     * 
     * @param array $attributes Record attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        return $this->getModel()->create($attributes);
    }

    /**
     * Update a record
     * 
     * @param mixed $id Record ID
     * @param array $attributes Attributes to update
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function update($id, array $attributes = [])
    {
        $model = $this->find($id);
        if ($model) {
            $model->update($attributes);
            return $model;
        }
        return null;
    }

    /**
     * Delete a record
     * 
     * @param mixed $id Record ID
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->find($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }

    /**
     * Search records
     * 
     * @param string $term Search term
     * @param array|null $columns Columns to search (uses config if null)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($term, $columns = null)
    {
        if (!$columns) {
            $columns = $this->config['searchable'];
        }

        if (empty($columns)) {
            return collect();
        }

        $query = $this->query();
        $query->where(function ($q) use ($term, $columns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'LIKE', "%{$term}%");
            }
        });

        $this->applyScopes($query);
        return $query->get();
    }

    /**
     * Filter records by allowed filters
     * 
     * @param array $filters Filters to apply
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(array $filters)
    {
        $query = $this->query();

        foreach ($filters as $key => $value) {
            if (in_array($key, $this->config['filters']) && $value !== null) {
                $query->where($key, $value);
            }
        }

        $this->applyScopes($query);
        return $query;
    }

    /**
     * Sort records by column
     * 
     * @param string $column Column to sort by
     * @param string $direction Sort direction (asc|desc)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sort($column, $direction = 'asc')
    {
        if (in_array($column, $this->config['sortable'])) {
            $query = $this->query();
            $this->applyScopes($query);
            return $query->orderBy($column, $direction);
        }

        return $this->query();
    }

    /**
     * Apply configured scopes to query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    protected function applyScopes($query)
    {
        foreach ($this->config['scopes'] as $scope => $params) {
            if (is_numeric($scope)) {
                $query->$params();
            } else {
                $query->$scope(...(array) $params);
            }
        }
    }

    /**
     * Load relationships
     * 
     * @param array|string|null $relations Relations to load (uses config if null)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function withRelations($relations = null)
    {
        if (!$relations) {
            $relations = $this->config['relations'];
        }

        $query = $this->query();
        $this->applyScopes($query);
        return $query->with($relations);
    }

    /**
     * Get configuration value(s)
     * 
     * @param string|null $key Specific config key or null for all
     * @return mixed
     */
    public function getConfig($key = null)
    {
        if ($key) {
            return $this->config[$key] ?? null;
        }
        return $this->config;
    }

    /**
     * Set configuration value
     * 
     * @param string $key Config key
     * @param mixed $value Config value
     * @return self
     */
    public function setConfig($key, $value)
    {
        $this->config[$key] = $value;
        return $this;
    }

    /**
     * Get the model class name
     * 
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }
}