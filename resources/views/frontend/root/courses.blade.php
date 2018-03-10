@extends('frontend.layout.layout')

@section('header_title')  @endsection

@section('title','三人行')
@section('header','三人行必有我师焉')
@section('description','课程集')

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    {{--课程s--}}
    @foreach($datas as $num => $course)
        @include('frontend.component.course')
    @endforeach

    {{ $datas->links() }}

@endsection


@section('style')
    <style>
        .box-footer a {cursor:pointer;}
    </style>
@endsection
@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
