@extends('frontend.layout.layout')

@section('header_title')  @endsection

@section('title','三人行')
@section('header','三人行必有我师焉')
@section('description','课程集')

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-sm-12 col-md-9 container-body-left">

            {{--@foreach($courses as $num => $course)--}}
            {{--@include('frontend.component.course')--}}
            {{--@endforeach--}}
            @include('frontend.component.course')

            {{ $courses->links() }}

        </div>

        <div class="col-sm-12 col-md-3 hidden-xs hidden-sm container-body-right">

            <div class="box-body right-menu" style="background:#fff;">

                <a href="{{url('/home')}}">
                    <div class="box-body {{ $menu_all or '' }}">
                        <i class="fa fa-home text-orange"></i> <span>&nbsp; 返回我的后台</span>
                    </div>
                </a>

            </div>

        </div>

    </div>

@endsection


@section('style')
<style>
</style>
@endsection
@section('js')
<script>
    $(function() {
        $('article').readmore({
            speed: 150,
            moreLink: '<a href="#">展开更多</a>',
            lessLink: '<a href="#">收起</a>'
        });
    });
</script>
@endsection

