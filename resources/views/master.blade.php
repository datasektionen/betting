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

    <link rel="apple-touch-icon" sizes="57x57" href="/images/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/images/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/icons/favicon-16x16.png">
    <link rel="manifest" href="/images/icons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffc107">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffc107">
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
