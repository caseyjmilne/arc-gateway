<?php

namespace ARC\Gateway\Endpoints\Standard;

use ARC\Gateway\Endpoints\BaseEndpoint;
use WP_REST_Request;
use WP_REST_Response;

class GetManyRoute extends BaseEndpoint
{

    public function getType()
    {
        return 'get_many';
    }

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

            if (!in_array($order, ['asc', 'desc'])) {
                $order = 'asc';
            }

            // ✅ Instantiate Query with the collection
            $query = new \ARC\Gateway\Query($this->collection);

            // ✅ Apply filters only if provided and allowed
            $filters = $request->get_params();
            foreach ($filters as $key => $value) {
                if (!in_array($key, ['page', 'per_page', 'order_by', 'order', 'search']) && $value !== null) {
                    $query->addParam($key, $value);
                }
            }

            // ✅ Apply search if collection supports it
            if ($search && method_exists($this->collection, 'search')) {
                $results = $this->collection->search($search);
                $total = count($results);

                // Convert to arrays
                $items = array_map(function ($model) {
                    return is_object($model) && method_exists($model, 'toArray')
                        ? $model->toArray()
                        : (array) $model;
                }, $results);

                // ✅ Manual pagination when using search
                $offset = ($page - 1) * $per_page;
                $paginatedItems = array_slice($items, $offset, $per_page);

                $response = [
                    'returned_count' => count($paginatedItems),
                    'items' => $paginatedItems,
                    'pagination' => [
                        'page' => $page,
                        'per_page' => $per_page,
                        'record_count' => $total,
                        'total_pages' => ceil($total / $per_page)
                    ]
                ];

                return $this->sendSuccessResponse($response);
            }

            // ✅ Order if valid
            if ($order_by) {
                $query->setOrder($order_by, $order);
            }

            // ✅ Pagination
            $offset = ($page - 1) * $per_page;
            $query
                ->setLimit($per_page)
                ->setOffset($offset);

            // ✅ Get results (Eloquent builder)
            $models = $query->get();
            $items = [];

            foreach ($models as $model) {
                $items[] = is_object($model) && method_exists($model, 'toArray')
                    ? $model->toArray()
                    : (array) $model;
            }

            // ✅ Count total without pagination
            $total = $this->collection->query()->count();

            $response = [
                'returned_count' => count($items),
                'data' => [
                    'items' => $items,
                    'pagination' => [
                        'page' => $page,
                        'per_page' => $per_page,
                        'record_count' => $total,
                        'total_pages' => ceil($total / $per_page)
                    ]
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