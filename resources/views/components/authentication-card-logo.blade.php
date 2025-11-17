 @php
        $system = \App\Models\Setting::find(1);
    @endphp
<a href="{{route('home')}}">
    <img
        src="{{ Image_path($system->logo) }}"
        alt="Logo"
        class="w-16 h-16 object-contain"
    />
</a>
