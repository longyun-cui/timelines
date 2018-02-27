@extends('frontend.layout.layout')

@section('title','三人行')
@section('header','三人行必有我师焉')
@section('description','课程集')

@section('content')
<div style="display:none;">
    <input type="hidden" id="" value="{{$encode or ''}}" readonly>
</div>

{{--作者--}}
@foreach($datas as $num => $data)
    <div class="row course-option">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="box panel-default
                @if($loop->index % 7 == 0) box-info
                @elseif($loop->index % 7 == 1) box-danger
                @elseif($loop->index % 7 == 2) box-success
                @elseif($loop->index % 7 == 3) box-default
                @elseif($loop->index % 7 == 4) box-warning
                @elseif($loop->index % 7 == 5) box-primary
                @elseif($loop->index % 7 == 6) box-danger
                @endif
            ">

                <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                    <h3 class="box-title">
                        <a href="{{url('/course/'.encode($data->id))}}">{{$data->title or ''}}</a>
                    </h3>
                    <span>来自 <a href="{{url('/u/'.encode($data->user->id))}}">{{$data->user->name or ''}}</a></span>
                    <span class="pull-right"><a class="show-menu" style="cursor:pointer">查看目录</a></span>
                </div>

                <div class="box-body menu-container" style="display:none;border-bottom:1px solid #ddd;">
                    <div class="colo-md-12 text-muted" style="margin-bottom:16px;">目录结构</div>
                    @foreach($data->contents as $content)
                        <div class="colo-md-12 box-body" style="padding:4px 10px;">
                            <i class="fa fa-list-ol"></i> &nbsp; {{ $content->title or '' }} </div>
                    @endforeach
                </div>

                @if(!empty($data->description))
                    <div class="box-body text-muted">
                        <div class="colo-md-12"> {!! $data->description or '' !!} </div>
                    </div>
                @endif

                @if(!empty($data->content))
                    <div class="box-body">
                        <div class="colo-md-12"> {!! $data->content or '' !!} </div>
                    </div>
                @endif


                <div class="box-footer">
                    &nbsp;
                </div>

            </div>
            <!-- END PORTLET-->
        </div>
    </div>
@endforeach

{{ $datas->links() }}

@endsection


@section('js')
    <script>
        $(function() {
            $('.course-option').on('click', '.show-menu', function () {
                var course_option = $(this).parents('.course-option');
                course_option.find('.menu-container').show();
                $(this).removeClass('show-menu').addClass('hide-menu');
                $(this).html('隐藏目录');
            });
            $('.course-option').on('click', '.hide-menu', function () {
                var course_option = $(this).parents('.course-option');
                course_option.find('.menu-container').hide();
                $(this).removeClass('hide-menu').addClass('show-menu');
                $(this).html('查看目录');
            });
        });
    </script>
@endsection
