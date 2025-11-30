<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccountsExport implements FromArray, WithHeadings
{
    protected $rows;

    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows->map(function ($acc) {
            return [
                'Email'    => $acc->email,
                'Meta'     => json_encode($acc->meta),
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return ['Email', 'Meta'];
    }
}
