<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BootStrap</title>
        <link rel="stylesheet" href="{{URL::asset('/bootstrap/css/bootstrap.min.css')}}">
</head>
<body>
    <div class="container" style="width: 504px;">
            @yield('content')
    </div>
@section('footer')
    <script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
@show
</body>
</html>