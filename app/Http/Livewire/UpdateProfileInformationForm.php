<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm as BaseForm;

class UpdateProfileInformationForm extends BaseForm
{
    public $countryCode = '+1';
    public $phoneNumber;

    protected $listeners = ['setCountryCodeProfile' => 'setCountryCode'];

    public function setCountryCode(string $code): void
    {
        $this->countryCode = $code;
    }

    public function mount()
    {
        parent::mount();

        $user = Auth::user();
        $this->splitPhone($user->phone);
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        $this->state['phone'] = $this->combinePhone();

        $updater->update(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            return redirect()->route('profile.show');
        }

        $this->dispatch('saved');
        $this->dispatch('refresh-navigation-menu');
    }

    private function splitPhone(?string $phone): void
    {
        $phone = trim((string) $phone);
        if ($phone === '') {
            return;
        }

        if (Str::startsWith($phone, '+')) {
            foreach ($this->dialCodesSorted() as $code) {
                if (Str::startsWith($phone, $code)) {
                    $this->countryCode = $code;
                    $this->phoneNumber = trim(substr($phone, strlen($code)));
                    return;
                }
            }
        }

        $this->phoneNumber = $phone;
    }

    private function combinePhone(): string
    {
        $code = preg_replace('/\s+/', '', (string) $this->countryCode);
        $num = preg_replace('/[^\d]/', '', (string) $this->phoneNumber);

        return $code . $num;
    }

    private function dialCodesSorted(): array
    {
        $codes = collect(config('country_codes', []))
            ->pluck('dial_code')
            ->filter(fn($code) => is_string($code) && $code !== '')
            ->unique()
            ->values()
            ->all();

        usort($codes, fn($a, $b) => strlen($b) <=> strlen($a));

        return $codes;
    }

    public function render()
    {
        return view('profile.update-profile-information-form', [
            'countryCodes' => $this->countryDialOptions(),
        ]);
    }

    private function countryDialOptions(): array
    {
        $preferredIso2ByDial = [
            '+1' => 'US',
            '+44' => 'GB',
            '+61' => 'AU',
            '+7' => 'RU',
            '+91' => 'IN',
            '+880' => 'BD',
        ];

        return collect(config('country_codes', []))
            ->filter(fn ($c) => is_array($c) && isset($c['dial_code'], $c['iso2']))
            ->groupBy('dial_code')
            ->map(function ($group, $dial) use ($preferredIso2ByDial) {
                $preferredIso2 = $preferredIso2ByDial[$dial] ?? null;

                if ($preferredIso2) {
                    $match = $group->firstWhere('iso2', $preferredIso2);
                    if ($match) {
                        return [
                            'dial_code' => $match['dial_code'],
                            'iso2' => $match['iso2'],
                            'name' => $match['name'] ?? $match['iso2'],
                        ];
                    }
                }

                $first = $group->first();

                return [
                    'dial_code' => $first['dial_code'],
                    'iso2' => $first['iso2'],
                    'name' => $first['name'] ?? $first['iso2'],
                ];
            })
            ->values()
            ->sortBy('dial_code')
            ->values()
            ->all();
    }
}
