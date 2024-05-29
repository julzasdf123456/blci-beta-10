<?php

use Illuminate\Support\Facades\Session;

if (!function_exists('setSession')) {
    function setSession($key, $value)
    {
        Session::put($key, $value);
    }
}

if (!function_exists('getSession')) {
    function getSession($key, $default = null)
    {
        return Session::get($key, $default);
    }
}

if (!function_exists('forgetSession')) {
    function forgetSession($key)
    {
        Session::forget($key);
    }
}