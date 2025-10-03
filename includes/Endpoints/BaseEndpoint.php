<?php

namespace ARC\Gateway\Endpoints;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use ARC\Gateway\Collection;

abstract class BaseEndpoint
{
    protected $collection;
    protected $collectionName;
    protected $namespace = 'arc-gateway/v1';

    public function __construct(Collection $collection, $collectionName)
    {
        $this->collection = $collection;
        $this->collectionName = $collectionName;
    }

    abstract public function getMethod();
    abstract public function getRoute();
    abstract public function handle(WP_REST_Request $request);

    abstract public function getType();

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getFullRoute()
    {
        return $this->getNamespace() . '/' . $this->collectionName . $this->getRoute();
    }

    public function getArgs()
    {
        return [
            'methods' => $this->getMethod(),
            'callback' => [$this, 'handle'],
            'permission_callback' => [$this, 'checkPermissions'], // Custom permission check for testing
        ];
    }

    public function checkPermissions($request)
    {
        // Get route-specific permissions from collection config
        $routeConfig = $this->collection->getRoutes();
        $permissions = $routeConfig['permissions'] ?? [];
        $routeType = $this->getType();

        // Determine which permission config to use
        $permissionConfig = null;

        // Check for route-specific permission
        if (isset($permissions[$routeType])) {
            $permissionConfig = $permissions[$routeType];
        }
        // Fall back to wildcard
        elseif (isset($permissions['*'])) {
            $permissionConfig = $permissions['*'];
        }

        // No permission config = require login by default
        if (!$permissionConfig) {
            if (!is_user_logged_in()) {
                return new WP_Error(
                    'rest_forbidden',
                    'You must be logged in to access this resource.',
                    ['status' => rest_authorization_required_code()]
                );
            }
            return true;
        }

        // Public access (permission set to false)
        if ($permissionConfig === false) {
            return true;
        }

        // Get auth type and settings
        $authType = $permissionConfig['type'] ?? 'cookie_authentication';
        $settings = $permissionConfig['settings'] ?? [];

        // Route to appropriate auth handler
        switch ($authType) {
            case 'cookie_authentication':
                return $this->checkCookieAuthentication($settings);

            case 'jwt':
                return $this->checkJWTAuthentication($settings);

            default:
                return new WP_Error(
                    'invalid_auth_type',
                    sprintf('Unknown authentication type: %s', $authType),
                    ['status' => 500]
                );
        }
    }

    protected function checkCookieAuthentication($settings)
    {
        $capability = $settings['capability'] ?? null;

        // If no capability specified, just require login
        if (!$capability) {
            if (!is_user_logged_in()) {
                return new WP_Error(
                    'rest_forbidden',
                    'You must be logged in to access this resource.',
                    ['status' => rest_authorization_required_code()]
                );
            }
            return true;
        }

        // Check if user has required capability
        if (!current_user_can($capability)) {
            $message = is_user_logged_in()
                ? sprintf('You need the "%s" capability to perform this action.', $capability)
                : 'You must be logged in to access this resource.';

            return new WP_Error(
                'rest_forbidden',
                $message,
                ['status' => rest_authorization_required_code()]
            );
        }

        return true;
    }

    protected function checkJWTAuthentication($settings)
    {
        // Placeholder for future JWT implementation
        return new WP_Error(
            'not_implemented',
            'JWT authentication is not yet implemented.',
            ['status' => 501]
        );
    }

    protected function sendSuccessResponse($data, $status = 200)
    {
        return new WP_REST_Response([
            'success' => true,
            'data' => $data
        ], $status);
    }

    protected function sendErrorResponse($message, $code = 'error', $status = 400)
    {
        return new WP_Error($code, $message, ['status' => $status]);
    }

    protected function sendMockResponse($operation, $data = null)
    {
        $mockData = [
            'operation' => $operation,
            'collection' => $this->collectionName,
            'timestamp' => current_time('mysql'),
            'mock' => true
        ];

        if ($data) {
            $mockData['input'] = $data;
        }

        switch ($operation) {
            case 'get_one':
                $mockData['result'] = [
                    'id' => 1,
                    'title' => 'Sample ' . ucfirst($this->collectionName),
                    'status' => 'active',
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ];
                break;
            case 'get_many':
                $mockData['result'] = [
                    'items' => [
                        [
                            'id' => 1,
                            'title' => 'Sample ' . ucfirst($this->collectionName) . ' 1',
                            'status' => 'active',
                            'created_at' => current_time('mysql'),
                            'updated_at' => current_time('mysql')
                        ],
                        [
                            'id' => 2,
                            'title' => 'Sample ' . ucfirst($this->collectionName) . ' 2',
                            'status' => 'active',
                            'created_at' => current_time('mysql'),
                            'updated_at' => current_time('mysql')
                        ]
                    ],
                    'total' => 2,
                    'page' => 1,
                    'per_page' => 10
                ];
                break;
            case 'create':
                $mockData['result'] = array_merge([
                    'id' => rand(100, 999),
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ], $data ?: []);
                break;
            case 'update':
                $mockData['result'] = array_merge([
                    'id' => $data['id'] ?? 1,
                    'updated_at' => current_time('mysql')
                ], $data ?: []);
                break;
            case 'delete':
                $mockData['result'] = [
                    'deleted' => true,
                    'id' => $data['id'] ?? 1
                ];
                break;
        }

        return $this->sendSuccessResponse($mockData);
    }

    public function getCollectionName()
    {
        return $this->collectionName;
    }

}