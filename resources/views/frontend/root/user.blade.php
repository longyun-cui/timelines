@extends('frontend.layout.layout')

@section('header_title')  @endsection

@section('title') {{$data->name}}的主页 @endsection
@section('header') {{$data->name}} @endsection
@section('description','主页')
@section('breadcrumb')
@endsection

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$_encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right container-body-sidebar pull-right" style="margin-bottom:16px;">

            <div class="box-body hover-box" style="background:#fff;">
                <i class="fa fa-user text-orange"></i>&nbsp; <b>{{ $data->name or '' }}</b>
            </div>

            <div class="box-body" style="margin-top:8px;background:#fff;">
                <div class="margin">课程数：{{ $data->lines_count or 0 }}</div>
                <div class="margin">访问量：{{ $data->visit_num or 0 }}</div>
            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-9 container-body-left">

            @include('frontend.component.lines')
            {{ $lines->links() }}

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
//        $('article').readmore({
//            speed: 150,
//            moreLink: '<a href="#">展开更多</a>',
//            lessLink: '<a href="#">收起</a>'
//        });
    });
</script>
@endsection
