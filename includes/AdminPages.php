<?php

namespace ARC\Gateway;

if (!defined('ABSPATH')) exit;

class AdminPage
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
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

    public function enqueue_admin_assets($hook)
    {
        // Only load on our admin page
        if ($hook !== 'toplevel_page_arc-gateway') {
            return;
        }

        $asset_file_path = ARC_GATEWAY_PATH . 'apps/admin/build/index.asset.php';

        if (!file_exists($asset_file_path)) {
            return;
        }

        $asset_file = include $asset_file_path;

        wp_enqueue_script(
            'arc-gateway-admin',
            ARC_GATEWAY_URL . 'apps/admin/build/index.js',
            $asset_file['dependencies'],
            $asset_file['version'],
            true
        );

        wp_enqueue_style(
            'arc-gateway-admin',
            ARC_GATEWAY_URL . 'apps/admin/build/style-index.css',
            [],
            $asset_file['version']
        );

        // Pass nonce and other data to JavaScript
        wp_localize_script(
            'arc-gateway-admin',
            'arcGatewayAdmin',
            [
                'nonce' => wp_create_nonce('wp_rest'),
                'apiUrl' => rest_url('arc-gateway/v1/')
            ]
        );
    }

    public function render_admin_page()
    {
        echo '<div class="wrap">';
        echo '<div id="arc-gateway-admin-root"></div>';
        echo '</div>';
    }
}
