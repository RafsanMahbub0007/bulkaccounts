<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderAccountsExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $accounts;
    protected $headers = [];

    public function __construct(Collection $accounts)
    {
        $this->accounts = $accounts;
        
        // Calculate all unique headers
        $allHeaders = ['Product', 'Email'];
        foreach ($accounts as $account) {
            if (!empty($account->meta_headers) && is_array($account->meta_headers)) {
                foreach ($account->meta_headers as $header) {
                     // Normalize header: ucfirst, trim
                     $h = ucfirst(trim($header));
                     // Avoid duplicates
                     if (!in_array($h, $allHeaders)) {
                         $allHeaders[] = $h;
                     }
                }
            }
        }
        $this->headers = $allHeaders;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function array(): array
    {
        return $this->accounts->map(function ($account) {
            $row = [];
            
            // Map the headers to values for this account
            $accountMetaMap = [];
            $meta = $account->meta ?? [];
            $metaHeaders = $account->meta_headers ?? [];
            
            foreach ($metaHeaders as $i => $h) {
                if (isset($meta[$i])) {
                    // Use same normalization as in constructor
                    $accountMetaMap[ucfirst(trim($h))] = $meta[$i];
                }
            }

            foreach ($this->headers as $header) {
                if ($header === 'Product') {
                    $row[] = $account->product->name ?? 'Unknown Product';
                } elseif ($header === 'Email') {
                    $row[] = $account->email;
                } else {
                    $row[] = $accountMetaMap[$header] ?? '';
                }
            }
            
            return $row;
        })->toArray();
    }
}
