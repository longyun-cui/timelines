@extends('home.layout.layout')

@section('header_title')  @endsection

@section('title','消息列表')
@section('header','消息')
@section('description','列表')

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
