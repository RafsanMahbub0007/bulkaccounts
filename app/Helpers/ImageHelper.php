<?php

if (!function_exists('image_path')) {
    function image_path($file, array $params = [])
    {
        if (!$file) {
            return '';
        }

        if (is_string($file) && (str_starts_with($file, 'http://') || str_starts_with($file, 'https://') || str_starts_with($file, '//') || str_starts_with($file, 'data:'))) {
            return $file;
        }

        $normalized = ltrim((string) $file, '/');
        $normalized = preg_replace('#^storage/app/public/#', '', $normalized);
        $normalized = preg_replace('#^storage/#', '', $normalized);

        $ext = strtolower(pathinfo($normalized, PATHINFO_EXTENSION));
        if (in_array($ext, ['svg', 'ico', 'gif'], true)) {
            return asset('storage/' . $normalized);
        }

        $url = route('image', ['path' => $normalized]);

        if ($params) {
            $query = http_build_query(array_filter($params, fn ($value) => $value !== null && $value !== ''));
            if ($query !== '') {
                $url .= '?' . $query;
            }
        }

        return $url;
    }
}
