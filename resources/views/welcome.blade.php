<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>F-BLOG</title>
        
        <link rel="stylesheet" href="/css/all.css">
        <script>
            (function () {
                window.Laravel = {
                    csrf_token : '{{ csrf_token() }}',
                };
            })();
        </script>
    </head>
    <body> 
        <div id="app">
            <main_app></main_app>
        </div>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
