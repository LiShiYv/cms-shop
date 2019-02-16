@extends('layouts.new')

@section('content')
    <div class="container">
        <ul>
            @foreach($list as $k=>$v)
                <li> 用户ID：{{$v->id}}  --  用户名称：{{$v->u_name}}
                    <a href="/goods/detail/{{$v->id}}" >{{$v->u_name}}</a><br><br>
                </li>
            @endforeach
        </ul>
    </div>
    {{$list->links()}}
@endsection