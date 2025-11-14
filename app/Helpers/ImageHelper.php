<?php

if (!function_exists('image_path')) {
    function image_path($file)
    {

        $base = env('ENV_IMAGE')
            ? 'storage/app/public/'
            : 'storage/';

        return asset($base . $file);
    }
}
