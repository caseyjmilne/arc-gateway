<?php

namespace ARC\Gateway;

class CollectionRegistry
{
    protected $collections = [];
    protected $aliases = [];

    public function register($modelClass, $config = [], $alias = null)
    {
        if (!class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class {$modelClass} does not exist");
        }

        $collection = new Collection($modelClass, $config);
        $this->collections[$modelClass] = $collection;

        if ($alias) {
            if (isset($this->aliases[$alias])) {
                throw new \InvalidArgumentException("Alias '{$alias}' is already registered for {$this->aliases[$alias]}");
            }
            $this->aliases[$alias] = $modelClass;
        }

        // FIXED: Pass alias as name, modelClass as model, and config array (not collection object)
        do_action('arc_gateway_collection_registered', $alias, $modelClass, $config);

        return $collection;
    }

    public function get($identifier)
    {
        if (isset($this->aliases[$identifier])) {
            $identifier = $this->aliases[$identifier];
        }

        if (!isset($this->collections[$identifier])) {
            throw new \InvalidArgumentException("Collection for '{$identifier}' is not registered");
        }

        return $this->collections[$identifier];
    }

    public function has($identifier)
    {
        if (isset($this->aliases[$identifier])) {
            $identifier = $this->aliases[$identifier];
        }

        return isset($this->collections[$identifier]);
    }

    public function unregister($identifier)
    {
        if (isset($this->aliases[$identifier])) {
            $modelClass = $this->aliases[$identifier];
            unset($this->aliases[$identifier]);
            $identifier = $modelClass;
        }

        if (isset($this->collections[$identifier])) {
            unset($this->collections[$identifier]);

            foreach ($this->aliases as $alias => $modelClass) {
                if ($modelClass === $identifier) {
                    unset($this->aliases[$alias]);
                }
            }

            do_action('arc_gateway_collection_unregistered', $identifier);
            return true;
        }

        return false;
    }

    public function getAll()
    {
        return $this->collections;
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function getRegistered()
    {
        return array_keys($this->collections);
    }

    public function getAlias($modelClass)
    {
        return array_search($modelClass, $this->aliases) ?: null;
    }

    public function count()
    {
        return count($this->collections);
    }

    public function clear()
    {
        $this->collections = [];
        $this->aliases = [];
        do_action('arc_gateway_registry_cleared');
    }

    public function batch($registrations)
    {
        foreach ($registrations as $registration) {
            $modelClass = $registration['model'] ?? null;
            $config = $registration['config'] ?? [];
            $alias = $registration['alias'] ?? null;

            if ($modelClass) {
                $this->register($modelClass, $config, $alias);
            }
        }
    }

    public function registerFromConfig($configFile)
    {
        if (!file_exists($configFile)) {
            throw new \InvalidArgumentException("Config file {$configFile} does not exist");
        }

        $config = include $configFile;

        if (!is_array($config)) {
            throw new \InvalidArgumentException("Config file must return an array");
        }

        $this->batch($config);
    }

    public function export()
    {
        $export = [];

        foreach ($this->collections as $modelClass => $collection) {
            $alias = $this->getAlias($modelClass);
            $export[] = [
                'model' => $modelClass,
                'config' => $collection->getConfig(),
                'alias' => $alias
            ];
        }

        return $export;
    }
}