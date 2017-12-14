<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/frontend.css">

    <title>Document</title>
</head>
<body>

<div id="wrap" class="wrapper">
    <div id="main" class="container">
        <div class="wrapper">
            <div class="content">
                <div id="app">
                    {!! $content !!}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/app.js"></script>
<script src="/js/behaviours.js"></script>
</body>
</html>
