<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Application</title>
    @vite('resources/css/app.css')
</head>
<body>
<div class="container">
    @yield('content')
</div>
@vite('resources/js/app.js')
</body>
</html>