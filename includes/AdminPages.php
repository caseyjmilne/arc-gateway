<?php

namespace ARC\Gateway;

if (!defined('ABSPATH')) exit;

class AdminPage
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_page']);
    }

    public function register_admin_page()
    {
        add_menu_page(
            'ARC Gateway',                 // Page title
            'ARC Gateway',                 // Menu title
            'manage_options',              // Capability
            'arc-gateway',                 // Menu slug
            [$this, 'render_admin_page'],  // Callback
            'dashicons-admin-generic',     // Icon
            90                             // Position
        );
    }

    public function render_admin_page()
    {
        echo '<div class="wrap">';
        echo '<h1>ARC Gateway Admin</h1>';

        // Collections
        $registry = Plugin::getInstance()->getRegistry();
        $collections = $registry->getAll(); // ✅ correct method
        echo '<h2>Registered Collections</h2>';
        if (!empty($collections)) {
            echo '<ul>';
            foreach ($collections as $alias => $collection) {
                echo '<li><strong>' . esc_html($alias) . '</strong> (' . esc_html(get_class($collection)) . ')</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No collections registered.</p>';
        }

        // Routes
        $routes = Plugin::getInstance()->getStandardRoutes()->getRouteInfo();
        echo '<h2>Registered Routes</h2>';
        if (!empty($routes)) {
            foreach ($routes as $collectionName => $endpoints) {
                echo '<h3>' . esc_html($collectionName) . '</h3>';
                echo '<ul>';
                foreach ($endpoints as $route) {
                    printf(
                        '<li><strong>%s</strong> (%s) — %s</li>',
                        esc_html($route['type']),
                        esc_html($route['method']),
                        esc_html($route['route'])
                    );
                }
                echo '</ul>';
            }
        } else {
            echo '<p>No routes registered.</p>';
        }

        echo '</div>';
    }
}
