<?php

if (!function_exists('ok')) {
    /**
     * Copy from Illuminate\Contracts\Routing\ResponseFactory
     *
     * @param  string|array|object  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    function ok(array $data = [], int $status = 200, array $headers = [], $options = 0)
    {
        $data = array_merge(['ok' => true], $data);
        return response()->json($data, $status, $headers, $options);
    }
}

if (!function_exists('fail')) {
    /**
     * Copy from Illuminate\Contracts\Routing\ResponseFactory
     *
     * @param  string|array|object  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    function fail(array $data = [], int $status = 200, array $headers = [], $options = 0)
    {
        $data = array_merge(['ok' => false], $data);
        return response()->json($data, $status, $headers, $options);
    }
}

if (!function_exists('jresponse')) {
    /**
     * Copy from Illuminate\Contracts\Routing\ResponseFactory
     *
     * @param  string|array|object  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     * @return \Illuminate\Http\JsonResponse
     */
    function jresponse($data = [], $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($data, $status, $headers, $options);
    }
}

if (!function_exists('regexp')) {
    function regexp($pattern, $subject)
    {
        return preg_match("/{$pattern}/", $subject);
    }
}

if (!function_exists('isAssocArray')) {
    function isAssocArray(array $arr)
    {
        if ([] === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
