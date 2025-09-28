<?php
/**
 * Plugin Name: ARC Gateway
 * Description: A plugin to register collections that extend Eloquent model functionality
 * Version: 1.0.0
 * Author: Developer
 * Namespace: ARC\Gateway
 */

namespace ARC\Gateway;

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ARC_GATEWAY_VERSION', '1.0.0');
define('ARC_GATEWAY_PATH', plugin_dir_path(__FILE__));
define('ARC_GATEWAY_URL', plugin_dir_url(__FILE__));
define('ARC_GATEWAY_FILE', __FILE__);

// Load classes immediately before instantiation
require_once ARC_GATEWAY_PATH . 'includes/CollectionRegistry.php';
require_once ARC_GATEWAY_PATH . 'includes/Collection.php';
require_once ARC_GATEWAY_PATH . 'includes/Gateway.php';
require_once ARC_GATEWAY_PATH . 'includes/StandardRoutes.php';
require_once ARC_GATEWAY_PATH . 'includes/Endpoints/BaseEndpoint.php';
require_once ARC_GATEWAY_PATH . 'includes/Endpoints/Standard/CreateRoute.php';
require_once ARC_GATEWAY_PATH . 'includes/Endpoints/Standard/GetOneRoute.php';
require_once ARC_GATEWAY_PATH . 'includes/Endpoints/Standard/GetManyRoute.php';
require_once ARC_GATEWAY_PATH . 'includes/Endpoints/Standard/UpdateRoute.php';
require_once ARC_GATEWAY_PATH . 'includes/Endpoints/Standard/DeleteRoute.php';
require_once ARC_GATEWAY_PATH . 'includes/AdminPages.php';
require_once ARC_GATEWAY_PATH . 'includes/Query.php';

// Include global helper functions
require_once ARC_GATEWAY_PATH . 'includes/helpers.php';

// Initiate classes that require initialization. 
new AdminPage();

class Plugin
{
    private static $instance = null;
    private $registry;
    private $standardRoutes;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->registry = new CollectionRegistry();
        $this->standardRoutes = new StandardRoutes();
        $this->init();
    }

    private function init()
    {
        register_activation_hook(ARC_GATEWAY_FILE, [$this, 'activate']);
        register_deactivation_hook(ARC_GATEWAY_FILE, [$this, 'deactivate']);

        // Hook for any initialization that needs to happen on 'init'
        add_action('init', [$this, 'onInit']);
    }

    public function onInit()
    {
        do_action('arc_gateway_loaded');
    }

    public function getRegistry()
    {
        return $this->registry;
    }

    public function getStandardRoutes()
    {
        return $this->standardRoutes;
    }

    public function activate()
    {
        flush_rewrite_rules();
    }

    public function deactivate()
    {
        flush_rewrite_rules();
    }
}

// Initialize plugin
Plugin::getInstance();
