<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>När slutar SM?</title>

    <link href="//aurora.datasektionen.se" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
</head>
<body>
    <div id="methone-container-replace"></div>
    <div id="application" class="amber">
        @yield('main')

        <div class="footer">
            "Det här ser ut som Forex hemsida" - Albin Remnestål 2017-10-17
        </div>
    </div>
    <script type="text/javascript">
        window.methone_conf = {
            system_name: "betting",
            color_scheme: "amber",
            @if(Auth::guest())
            login_text: "Logga in",
            login_href: "/login",
            @else
            login_text: "Logga ut {{ Auth::user()->kth_username }}",
            login_href: "/logout",
            @endif
            links: [
                { str: "Hem", href: "/" }
            ]
        }
    </script>
    <script src="//methone.datasektionen.se/bar.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
</body>
</html>
