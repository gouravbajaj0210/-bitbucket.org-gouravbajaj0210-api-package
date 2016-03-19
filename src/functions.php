<?php
if ( ! function_exists('api')) {

    function api($data = null)
    {
        $api = app('api');
        if ( ! is_null($data)) {
            return $api->success($data);
        }
        return $api;
    }
}