<?php

namespace ARC\Gateway\Endpoints\Standard;

use ARC\Gateway\Endpoints\BaseEndpoint;
use WP_REST_Request;
use WP_REST_Response;

class GetOneRoute extends BaseEndpoint
{
    public function getMethod()
    {
        return 'GET';
    }

    public function getRoute()
    {
        return '/(?P<id>\d+)';
    }

    public function handle(WP_REST_Request $request)
    {
        $id = $request->get_param('id');

        if (!$id) {
            return $this->sendErrorResponse(
                'ID parameter is required',
                'missing_id',
                400
            );
        }

        try {
            $model = $this->collection->find($id);

            if (!$model) {
                return $this->sendErrorResponse(
                    ucfirst($this->collectionName) . ' not found',
                    'not_found',
                    404
                );
            }

            // Convert model to array for response
            $responseData = is_object($model) && method_exists($model, 'toArray')
                ? $model->toArray()
                : (array) $model;

            return $this->sendSuccessResponse($responseData);

        } catch (\Exception $e) {
            error_log("ARC Gateway GetOneRoute Error: " . $e->getMessage());

            return $this->sendErrorResponse(
                'Failed to retrieve ' . $this->collectionName . ': ' . $e->getMessage(),
                'retrieval_failed',
                500
            );
        }
    }

    public function getArgs()
    {
        $args = parent::getArgs();

        $args['args'] = [
            'id' => [
                'required' => true,
                'type' => 'integer',
                'description' => 'The ID of the item to retrieve',
                'validate_callback' => function($param) {
                    return is_numeric($param) && $param > 0;
                },
            ],
        ];

        return $args;
    }
}