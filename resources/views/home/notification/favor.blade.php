@extends('home.layout.layout')

@section('header_title')  @endsection

@section('title','收到点赞')
@section('header','收到点赞')
@section('description','')

@section('content')

    @include('home.component.comment')

@endsection

@section('style')
@endsection
@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
