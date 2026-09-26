<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $dialCodes = $this->dialCodes();

        Validator::make($input, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'starts_with:+', 'max:50', function ($attribute, $value, $fail) use ($dialCodes) {
                $matched = false;
                foreach ($dialCodes as $code) {
                    if (str_starts_with($value, $code)) {
                        $rest = substr($value, strlen($code));
                        if (preg_match('/^\d{5,}$/', $rest)) {
                            $matched = true;
                            break;
                        }
                    }
                }
                if (!$matched) {
                    $fail('Phone number must include a valid country code starting with + and a valid local number.');
                }
            }],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name'  => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name'            => $input['name'],
            'email'           => $input['email'],
            'phone'           => $input['phone'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
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
