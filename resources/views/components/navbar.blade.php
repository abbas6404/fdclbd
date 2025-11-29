@props([
    'brand' => config('app.name'),
    'brandRoute' => route('home'),
    'theme' => 'light'
])

<nav class="navbar navbar-expand-lg navbar-{{ $theme }} bg-{{ $theme }} {{ $attributes->get('class') }}">
    <div class="container">
        <a class="navbar-brand" href="{{ $brandRoute }}">
            {{ $brand }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{ $slot }}
        </div>
    </div>
</nav> 