<html>
<head>
    <title>Weiliang-@yield('title')</title>
    <link rel="stylesheet" href="{{URL::asset('/css/test.css')}}">
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
@section('footer')
    <p style="color: blue">这是main的底部</p>
    <script src="{{URL::asset('/js/test.js')}}"></script>

</body>
</html>