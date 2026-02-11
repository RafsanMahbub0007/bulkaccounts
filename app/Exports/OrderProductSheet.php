<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class OrderProductSheet implements FromArray, WithHeadings, ShouldAutoSize, WithTitle
{
    protected $accounts;
    protected $title;
    protected $headers = [];

    public function __construct(Collection $accounts, string $title)
    {
        $this->accounts = $accounts;
        $this->title = $title;
        
        // Calculate all unique headers for this batch of accounts
        $allHeaders = ['Email'];
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

    public function title(): string
    {
        return substr($this->title, 0, 31); // Excel title limit
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
                if ($header === 'Email') {
                    $row[] = $account->email;
                } else {
                    $row[] = $accountMetaMap[$header] ?? '';
                }
            }
            
            return $row;
        })->toArray();
    }
}
