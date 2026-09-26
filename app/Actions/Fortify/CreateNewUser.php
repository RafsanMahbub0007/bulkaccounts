<?php

namespace App\Actions\Fortify;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        $dialCodes = $this->dialCodes();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role_id' => ['required', 'in:2,3'],
            'country_code' => ['required', 'string', 'in:' . implode(',', $dialCodes)],
            'phone_number' => ['required', 'string', 'max:50', function ($attr, $val, $fail) {
                $digits = preg_replace('/[^\d]/', '', (string) $val);
                if (strlen($digits) < 5) {
                    $fail('Phone number must contain at least 5 digits.');
                }
            }],
        ])->validate();

        $fullPhone = $this->combinePhone($input['country_code'] ?? '', $input['phone_number'] ?? '');

        $this->validateFullPhone($fullPhone, $dialCodes);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $fullPhone,
            'password' => Hash::make($input['password']),
            'role_id' => $input['role_id'],
        ]);

        if (Schema::hasTable('roles') && Schema::hasTable('role_user')) {
            $roleName = match ((int) $input['role_id']) {
                3 => 'seller',
                default => 'user',
            };

            if (Role::query()->where('name', $roleName)->exists()) {
                $user->assignRole($roleName);
            }
        }

        $user->markEmailAsVerified();
        Auth::login($user);

        return $user;
    }

    private function combinePhone(string $countryCode, string $number): string
    {
        $code = preg_replace('/\s+/', '', $countryCode);
        $num = preg_replace('/[^\d]/', '', $number);

        return $code . $num;
    }

    private function validateFullPhone(string $phone, array $dialCodes): void
    {
        $matched = false;
        foreach ($dialCodes as $code) {
            if (str_starts_with($phone, $code)) {
                $rest = substr($phone, strlen($code));
                if (preg_match('/^\d{5,}$/', $rest)) {
                    $matched = true;
                    break;
                }
            }
        }

        if (!$matched) {
            throw new \Illuminate\Validation\ValidationException(
                Validator::make([], [])
                    ->errors()
                    ->add('phone_number', 'Phone number must include a valid country code and local number.')
            );
        }
    }

    private function dialCodes(): array
    {
        return collect(config('country_codes', []))
            ->pluck('dial_code')
            ->filter(fn($code) => is_string($code) && $code !== '')
            ->unique()
            ->values()
            ->all();
    }
}
