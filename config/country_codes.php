<?php

$jsonPath = base_path('resources/data/country_dial_info.json');

if (!is_file($jsonPath)) {
    return [];
}

$data = json_decode(file_get_contents($jsonPath), true);

if (!is_array($data)) {
    return [];
}

return collect($data)
    ->filter(fn ($c) => is_array($c) && isset($c['code'], $c['name'], $c['dial_code'], $c['flag']))
    ->map(fn ($c) => [
        'iso2' => $c['code'],
        'name' => $c['name'],
        'dial_code' => $c['dial_code'],
        'flag' => $c['flag'],
    ])
    ->sortBy('name')
    ->values()
    ->all();

