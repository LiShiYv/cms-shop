@extends('layouts.new')


@section('content')
<form  action="vip/file" method="post" enctype="multipart/form-data" >
    <h1>上传</h1>
    {{csrf_field()}}
    <input type="file" name="pdf">
    <button type="submit" class="btn btn-default">上传</button>
</form>
@endsection