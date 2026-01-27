<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class UsersExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $users;

    public function __construct(Collection $users = null)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users ?? User::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Email Verified At',
            'Created At',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->email_verified_at,
            $user->created_at,
        ];
    }
}
