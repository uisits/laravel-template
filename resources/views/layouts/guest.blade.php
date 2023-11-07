<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('/favicon.ico') }}">

    <title>
        University of Illinois Springfield - UIS - {{ env('APP_NAME') }} - {{ $title ?? env('APP_NAME') }}
    </title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
    @if($styles ?? '')
        {{ $styles }}
    @endif
    <!-- Scripts -->
    <script src="{{ url(mix('js/app.js')) }}" defer></script>
</head>

<body class="font-sans antialiased">
    <div>
        <!-- Page Content -->
        <main class="bg-gray-100">
            {{ $slot }}
        </main>
    </div>
    @if($scripts ?? '')
        {{ $scripts }}
    @endif
</body>
</html>
