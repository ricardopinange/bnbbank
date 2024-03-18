<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Is http response valid?
     *
     * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     *
     * @param int $code
     * @return bool
     */
    public function isValidHttpResponse($code)
    {
        $intCode = intval($code);
        return $intCode >= 100 && $intCode < 600 && is_int($intCode);
    }

    /**
     * Display request success data.
     *
     * @return \Illuminate\Http\Response
     */
    public function success($data, $message = true)
    {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
                'data' => $data
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Display request error data.
     *
     * @return \Illuminate\Http\Response
     */
    public function error($e, $message = false)
    {
        error_log('Exception: ' . $e->getMessage());
        return response()->json(
            [
                'success' => false,
                'message' => $message,
                'errors' => is_array($e->getMessage())
                    ? $e->getMessage()
                    : [ $e->getMessage() ]
            ],
            $this->isValidHttpResponse($e->getCode())
                ? $e->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
