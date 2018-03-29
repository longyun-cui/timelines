@extends('home.layout.layout')

@section('title','用户主页 - 时间线')
@section('header','时间线')
@section('description','用户主页')
@section('breadcrumb')
    <li><a href="{{url('/home')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
home.index
@endsection


@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
