<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccountsExport implements FromArray, WithHeadings
{
    protected $rows;
    protected $metaKeys = [];

    public function __construct(Collection $rows)
    {
        $this->rows = $rows;

        // Collect all possible keys in meta
        foreach ($rows as $acc) {
            if (is_array($acc->meta)) {
                $this->metaKeys = array_unique(array_merge($this->metaKeys, array_keys($acc->meta)));
            }
        }
    }

    public function array(): array
    {
        return $this->rows->map(function ($acc) {
            $data = [
                'Email' => $acc->email,
            ];

            // Add each meta key as separate column
            foreach ($this->metaKeys as $key) {
                $data[$key] = $acc->meta[$key] ?? '';
            }

            return $data;
        })->toArray();
    }

    public function headings(): array
    {
        return array_merge(['Email'], $this->metaKeys);
    }
}
