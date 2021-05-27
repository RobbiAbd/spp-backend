<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    const HTTP_STATUS_ERROR         = 500;
    const HTTP_STATUS_SUCCESS       = 200;
    const HTTP_STATUS_CREATED       = 201;
    const HTTP_STATUS_NOT_FOUND     = 404;
    const HTTP_STATUS_UNAUTHORIZED  = 401;
    const HTTP_STATUS_FORBIDDEN     = 403;
    const HTTP_UNPROCESSABLE        = 422;

    /**
     * @param bool success?
     * @param string message
     * @param int status code
     */
    public function writeResponse($code, $message, $success)
    {
        return response()->json([
            'success' => $success,
            'message' => $message
        ], $code);
    }

    /**
     * @param bool success?
     * @param string message
     * @param int status code
     */
    public function writeResponseData($data)
    {
        return response()->json($data, 200);
    }

    /**
     * @param bool success?
     * @param string message
     * @param int status code
     */
    public function writeResponseBody($code, $message, $body, $success)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $body
        ], $code);
    }
}
