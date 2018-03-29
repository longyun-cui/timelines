@extends('frontend.layout.layout')

@section('header_title')  @endsection

@section('title') {{$point->title or ''}} @endsection
@section('header') {{$point->title or ''}} @endsection
@section('description') {{$point->title or ''}} @endsection

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right container-body-sidebar pull-right">

            <div class="box-body right-menu" style="background:#fff; margin-bottom:16px;">

                <a href="{{url('/line/'.encode($point->line->id))}}">
                    <div class="box-body hover-box">{{$point->line->title}}</div>
                </a>

            </div>

            <div class="box-body right-menu" style="background:#fff;">

                @if(!Auth::check())

                    <a href="{{url('/login')}}">
                        <div class="box-body hover-box">
                            <i class="fa fa-circle-o text-default"></i> <span>&nbsp; 登录</span>
                        </div>
                    </a>

                    <a href="{{url('/register')}}">
                        <div class="box-body hover-box">
                            <i class="fa fa-circle-o text-default"></i> <span>&nbsp; 注册</span>
                        </div>
                    </a>
                @else
                    <a href="{{url('/home')}}">
                        <div class="box-body hover-box">
                            <i class="fa fa-home text-orange"></i> <span>&nbsp; 返回我的后台</span>
                        </div>
                    </a>
                @endif

            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-9 container-body-left pull-left">

            @include('frontend.component.point',['data'=>$point])

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

        $(".comments-get-default").click();

    });
</script>
@endsection

