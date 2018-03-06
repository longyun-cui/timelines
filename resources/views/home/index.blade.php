@extends('home.layout.layout')

@section('title','用户主页 - 三人行')
@section('header','三人行')
@section('description','用户主页')
@section('breadcrumb')
    <li><a href="{{url('/home')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection

@section('content')
hone.index
@endsection


@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
