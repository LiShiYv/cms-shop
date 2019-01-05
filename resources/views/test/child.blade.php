@extends('layouts.mama')

@section('title') {{$title}}    @endsection

@section('header')
    @parent
    <p style="color: red;">This is Child header.</p>
@endsection

@section('content')
    <p>这里是 Child Content.
    <table border="1">
        <thead>
        <td>ID</td><td>Name</td><td>Age</td><td>Email</td><td>Reg_time</td>
        </thead>
        <tbody>
        @foreach($list as $v)
            <tr>
                <td>{{$v['id']}}</td><td>{{$v['u_name']}}</td><td>{{$v['age']}}</td><td>{{$v['u_email']}}</td><td>{{date('Y-m-d H:i:s',$v['reg_time'])}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection


@section('footer')
    @parent
    <p style="color: red;">This is Child footer .</p>
@endsection