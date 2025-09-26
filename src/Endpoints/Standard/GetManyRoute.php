<?php

namespace ARC\Gateway\Endpoints\Standard;

use ARC\Gateway\Endpoints\BaseEndpoint;
use WP_REST_Request;
use WP_REST_Response;

class GetManyRoute extends BaseEndpoint
{
    public function getMethod()
    {
        return 'GET';
    }

    public function getRoute()
    {
        return '';
    }

    public function handle(WP_REST_Request $request)
    {
        try {
            $page = max(1, (int) $request->get_param('page') ?: 1);
            $per_page = min(100, max(1, (int) $request->get_param('per_page') ?: 10));
            $search = $request->get_param('search');
            $order_by = $request->get_param('order_by');
            $order = strtolower($request->get_param('order') ?: 'asc');

            // Validate order direction
            if (!in_array($order, ['asc', 'desc'])) {
                $order = 'asc';
            }

            $models = [];
            $total = 0;

            if ($search) {
                // Use collection's search method
                $models = $this->collection->search($search);
                $total = count($models);
            } else {
                // Get all models using the collection
                $models = $this->collection->all();
                $total = count($models);
            }

            // Convert models to arrays
            $items = [];
            foreach ($models as $model) {
                $items[] = is_object($model) && method_exists($model, 'toArray')
                    ? $model->toArray()
                    : (array) $model;
            }

            // Apply sorting if specified and allowed
            if ($order_by && $this->collection->getConfig('sortable') && in_array($order_by, $this->collection->getConfig('sortable'))) {
                usort($items, function($a, $b) use ($order_by, $order) {
                    $aVal = $a[$order_by] ?? '';
                    $bVal = $b[$order_by] ?? '';

                    if ($order === 'desc') {
                        return $bVal <=> $aVal;
                    }
                    return $aVal <=> $bVal;
                });
            }

            // Apply pagination
            $offset = ($page - 1) * $per_page;
            $paginatedItems = array_slice($items, $offset, $per_page);

            $response = [
                'items' => $paginatedItems,
                'pagination' => [
                    'page' => $page,
                    'per_page' => $per_page,
                    'total' => $total,
                    'total_pages' => ceil($total / $per_page)
                ]
            ];

            return $this->sendSuccessResponse($response);

        } catch (\Exception $e) {
            error_log("ARC Gateway GetManyRoute Error: " . $e->getMessage());

            return $this->sendErrorResponse(
                'Failed to retrieve ' . $this->collectionName . ' items: ' . $e->getMessage(),
                'retrieval_failed',
                500
            );
        }
    }

    public function getArgs()
    {
        $args = parent::getArgs();

        $args['args'] = [
            'page' => [
                'default' => 1,
                'type' => 'integer',
                'minimum' => 1,
                'description' => 'Page number for pagination',
                'sanitize_callback' => 'absint',
            ],
            'per_page' => [
                'default' => 10,
                'type' => 'integer',
                'minimum' => 1,
                'maximum' => 100,
                'description' => 'Number of items per page',
                'sanitize_callback' => 'absint',
            ],
            'search' => [
                'type' => 'string',
                'description' => 'Search term to filter items',
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'order_by' => [
                'type' => 'string',
                'description' => 'Column to sort by',
                'sanitize_callback' => 'sanitize_key',
            ],
            'order' => [
                'type' => 'string',
                'default' => 'asc',
                'enum' => ['asc', 'desc'],
                'description' => 'Sort order (asc or desc)',
                'sanitize_callback' => 'sanitize_key',
            ],
        ];

        return $args;
    }
}