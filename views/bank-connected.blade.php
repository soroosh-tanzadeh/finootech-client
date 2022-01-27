<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>اتصال حساب مدیر</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            direction: rtl;
        }

        .status-container {
            display: flex;
            height: 100vh;
            width: 100vw;
            justify-content: center;
            align-items: center;
        }

        .status-failed {
            color: #b30000;
        }

        .status-done {
            color: #24b300;
        }

        .status-text {
            font-size: 32px;
        }

    </style>
</head>

<body class="antialiased">
    <div class="status-container">
        <strong class="{{ $status_color }}">{{ $status_text }}</strong>
    </div>
</body>

</html>
