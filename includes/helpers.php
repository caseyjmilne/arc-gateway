<?php
// Global helpers for ARC Gateway

function arc_register_collection($modelClass, $config = [], $alias = null)
{
    return \ARC\Gateway\Plugin::getInstance()->getRegistry()->register($modelClass, $config, $alias);
}

function arc_get_collection($identifier)
{
    return \ARC\Gateway\Gateway::get($identifier);
}

function arc_collection($identifier)
{
    return \ARC\Gateway\Gateway::collection($identifier);
}

function arc_query($identifier)
{
    return \ARC\Gateway\Gateway::query($identifier);
}

function arc_get_routes()
{
    return \ARC\Gateway\Plugin::getInstance()->getStandardRoutes()->getRouteInfo();
}

function arc_get_routes_for($collectionKey)
{
    $allRoutes = arc_get_routes();

    if (isset($allRoutes[$collectionKey])) {
        return $allRoutes[$collectionKey];
    }

    return [];
}

