<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $APP_NAME }}</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>

    <header>
        <h1>Meu site</h1>
        <nav>
            <!-- Links -->
        </nav>
    </header>

    <main>
        @yield('content')
    </main>


    <footer>
        <p>Todos os direitos reservados &copy; {{ date('Y') }}</p>
    </footer>

</body>
</html>