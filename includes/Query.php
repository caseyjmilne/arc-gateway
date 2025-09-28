<?php 

namespace ARC\Gateway;

if (!defined('ABSPATH')) exit;

class Query
{
    protected $collection;
    protected $params = [];
    protected $orderBy = null;
    protected $orderDir = 'asc';
    protected $limit = null;   // ✅ Added
    protected $offset = null;  // ✅ Added

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Add a param (filter) for the query
     */
    public function addParam($key, $value)
    {
        $allowedFilters = $this->collection->getConfig('filters') ?: [];
        if (in_array($key, $allowedFilters)) {
            $this->params[$key] = $value;
        }
        return $this;
    }

    /**
     * Set sorting
     */
    public function setOrder($column, $direction = 'asc')
    {
        $sortable = $this->collection->getConfig('sortable') ?: [];
        if (in_array($column, $sortable)) {
            $this->orderBy = $column;
            $this->orderDir = strtolower($direction) === 'desc' ? 'desc' : 'asc';
        }
        return $this;
    }

    /**
     * ✅ Set limit
     */
    public function setLimit($limit)
    {
        $this->limit = (int) $limit;
        return $this;
    }

    /**
     * ✅ Set offset
     */
    public function setOffset($offset)
    {
        $this->offset = (int) $offset;
        return $this;
    }

    /**
     * Build the Eloquent query
     */
    public function build()
    {
        $query = $this->collection->query();

        // Apply filters
        foreach ($this->params as $key => $value) {
            $query->where($key, $value);
        }

        // Apply order
        if ($this->orderBy) {
            $query->orderBy($this->orderBy, $this->orderDir);
        }

        // ✅ Apply offset
        if (!is_null($this->offset)) {
            $query->offset($this->offset);
        }

        // ✅ Apply limit
        if (!is_null($this->limit)) {
            $query->limit($this->limit);
        }

        return $query; // returns builder
    }

    /**
     * Run the query
     */
    public function get($columns = ['*'])
    {
        return $this->build()->get($columns);
    }

    /**
     * Debug: show the SQL and bindings
     */
    public function debug()
    {
        $query = $this->build();
        return [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
        ];
    }
}
