<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filament Page</title>
    @livewireStyles
</head>
<body>
    <div class="filament-page" style="margin-top: 20px">
        @yield('content')
    </div>

    @livewireScripts
</body>
</html>
