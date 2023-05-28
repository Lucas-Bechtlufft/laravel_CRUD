<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    {{-- Fonte do google --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">

    {{-- CSS BootStrap --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    {{-- CSS da Aplicação --}}
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/script.js"></script>
</head>

<body>
    @yield('content')
    <footer>
        <p>HDC Events &copy; 2023</p>
    </footer>
</body>

</html>
