<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tạo khảo sát - Khảo sát giá sản phẩm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScripts
</head>
<body class="antialiased">
    <livewire:khaosat.create-survey />
</body>
</html>
