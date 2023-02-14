<?php

namespace App\Utils;

class ResponseHelper
{
    static public  function success($message, $data)
    {
        return response()->json(
            [
                'result' => 1,
                'message' => $message,
                'data' => $data,
            ]
        );
    }

    static public function fail($message, $data)
    {
        return response()->json(
            [
                'result' => 0,
                'message' => $message,
                'data' => $data,
            ]
        );
    }
}
