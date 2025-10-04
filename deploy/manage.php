<?php
/**
 * Plugin deployment and update management
 */

namespace ARC\Gateway\Deploy;

if (!defined('ABSPATH')) {
    exit;
}

// Load Plugin Update Checker
require_once ARC_GATEWAY_PATH . 'deploy/plugin-update-checker/plugin-update-checker.php';

$arcGatewayUpdateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'http://arcwp.ca/release/arc-gateway/latest.json',
    ARC_GATEWAY_FILE,
    'arc-gateway'
);
