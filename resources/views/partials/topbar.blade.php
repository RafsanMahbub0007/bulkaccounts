<div class="bg-gray-900 text-white">
    <div class="container mx-auto px-6 lg:px-16 py-4 flex flex-col sm:flex-row justify-between items-center">
        <!-- Left Section: Contact Information -->
        <div class="flex flex-col sm:flex-row items-center sm:space-x-8 mb-4 sm:mb-0">
            <div class="flex items-center space-x-3 mb-2 sm:mb-0">
                <i class="fas fa-phone-alt text-red-500 text-lg"></i>
                <a href="tel:+1234567890" class="text-sm font-medium hover:text-gray-300 transition">
                    +1 234 567 890
                </a>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-envelope text-red-500 text-lg"></i>
                <a href="mailto:support@bulkaccounts.com" class="text-sm font-medium hover:text-gray-300 transition">
                    support@bulkaccounts.com
                </a>
            </div>
        </div>
@php
    $socialLinks = [
        'facebook' => ['url' => $system->f_link ?? null, 'icon' => 'facebook-f'],
        'telegram' => ['url' => $system->t_link ?? null, 'icon' => 'telegram'],
        'twitter' => ['url' => $system->tw_link ?? null, 'icon' => 'twitter'],
        'instagram' => ['url' => $system->i_link?? null,'icon' => 'instagram'],
        'linkedin' => ['url' => $system->lnkd_link ?? null, 'icon' => 'linkedin-in'],
        'youtube' => ['url' => $system->y_link  ?? null, 'icon' => 'youtube'],
    ];
@endphp

<div class="flex items-center space-x-4">
    @foreach ($socialLinks as $platform)
        @if (!empty($platform['url']))
            <a href="{{ $platform['url'] }}" target="_blank"
               class="w-8 h-8 flex items-center justify-center text-red-600 rounded-full bg-transparent border-2 border-red-600 hover:bg-red-600 hover:text-white transition">
                <i class="fab fa-{{ $platform['icon'] }} text-sm"></i>
            </a>
        @endif
    @endforeach
</div>

    </div>
</div>
