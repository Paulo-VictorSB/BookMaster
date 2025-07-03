<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>BookMaster</title>
    {{-- Css --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    {{-- Header --}}
    @include('layouts.headerHome')
    @yield('header')
    {{-- Hero section --}}
    @include('layouts.heroHome')
    @yield('heroHome')
    {{-- Main Content --}}
    @yield('main')

</body>
</html>