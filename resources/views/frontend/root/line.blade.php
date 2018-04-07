@extends('frontend.layout.layout')

@section('header_title')  @endsection

@section('title') {{$line->title or ''}} @endsection
@section('header') {{$line->title or ''}} @endsection
@section('description') line @endsection

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$encode or ''}}" readonly>
    </div>

    <div class="container">

        <div class="col-xs-12 col-sm-12 col-md-3 container-body-right container-body-sidebar pull-right">

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

            @include('frontend.component.lines')

        </div>

        <div class="col-xs-12 col-sm-12 col-md-9 container-body-left pull-left" style="margin-top:32px;margin-bottom:64px;">
            <ul class="timeline">

                @foreach($points as $po)
                {{--<!-- timeline time label -->--}}
                <li class="time-label">
                    <span class="bg-red"> {{$po->time or ''}} </span>
                </li>
                {{--<!-- /.timeline-label -->--}}

                {{--<!-- timeline item -->--}}
                <li class="item-piece item-option point-option {{$point_magnitude or 'item-plural'}}"
                    data-line="{{encode($line->id)}}"
                    data-point="{{encode($po->id)}}"
                >

                    {{--<!-- timeline icon -->--}}
                    <i class="fa fa-circle-o bg-blue"></i>
                    <div class="timeline-item">
                        <span class="time _none"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a target="_blank" href="{{url('/point/'.encode($po->id))}}">{{$po->title or ''}}</a></h3>

                        {{--description--}}
                        @if(!empty($po->description))
                            <div class="timeline-body" style="padding-bottom:0;">
                                <div class="colo-md-12 text-muted"> {{ $po->description or '' }} </div>
                            </div>
                        @endif

                        @if(!empty($po->content))
                        <div class="timeline-body">
                            <div class="media">
                                <div class="media-left">
                                    <img src="{!! $po->img_tags[2][0] or '' !!}" alt="" class="media-object">
                                </div>
                                <div class="media-body">
                                    <div class="clearfix">
                                        <article class="colo-md-12"> {!! $po->content_show or '' !!} </article>
                                    </div>
                                </div>
                            </div>
                            {{--<article class="colo-md-12 point-content"> {!! $po->content or '' !!} </article>--}}
                        </div>
                        @endif

                        <div class="timeline-footer box-footer item-tools-row">
                            {{--点赞--}}
                            <a class="margin favor-btn" data-num="{{$po->favor_num}}" role="button">
                                @if(Auth::check())
                                    @if($po->others->contains('type', 1))
                                        <span class="favor-this-cancel"><i class="fa fa-thumbs-up text-red"></i>
                                    @else
                                        <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                                    @endif
                                @else
                                    <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                                @endif

                                @if($po->favor_num) {{$po->favor_num}} @endif </span>
                            </a>

                            {{--收藏--}}
                            <a class="margin collect-btn" data-num="{{$po->collect_num}}" role="button">
                                @if(Auth::check())
                                    @if($po->user_id != Auth::id())
                                        @if(count($po->collections))
                                            <span class="collect-this-cancel"><i class="fa fa-heart text-red"></i>
                                        @else
                                            <span class="collect-this"><i class="fa fa-heart-o"></i>
                                        @endif
                                    @else
                                        <span class="collect-mine"><i class="fa fa-heart-o"></i>
                                    @endif
                                @else
                                    <span class="collect-this"><i class="fa fa-heart-o"></i>
                                @endif

                                @if($po->collect_num) {{$po->collect_num}} @endif </span>
                            </a>

                            <a class="margin _none" role="button">
                                <i class="fa fa-share"></i> @if($po->share_num) {{$po->share_num}} @endif
                            </a>

                            <a class="margin comment-toggle" role="button" data-num="{{$po->comment_num}}">
                                <i class="fa fa-commenting-o"></i> @if($po->comment_num) {{$po->comment_num}} @endif
                            </a>
                        </div>

                        {{--comment--}}
                        <div class="box-body comment-container" style="display:none;">

                            <input type="hidden" class="comments-get comments-get-default">

                            <div class="box-body comment-input-container">
                                <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                                    {{csrf_field()}}
                                    <input type="hidden" name="line_id" value="{{encode($line->id)}}" readonly>
                                    <input type="hidden" name="point_id" value="{{encode($po->id)}}" readonly>
                                    <input type="hidden" name="type" value="1" readonly>

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div><textarea class="form-control" name="content" rows="3" placeholder="请输入你的评论"></textarea></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 ">
                                            <button type="button" class="btn btn-block btn-flat btn-primary comment-submit">提交</button>
                                        </div>
                                    </div>

                                </form>
                            </div>


                            {{--评论列表--}}
                            <div class="box-body comment-entity-container">

                                <div class="comment-list-container">
                                </div>

                                <div class="col-md-12 more-box comment-more-box">
                                    <button type="button" class="btn btn-block btn-flat btn-more comments-more"></button>
                                </div>

                            </div>

                        </div>

                    </div>

                </li>
                {{--<!-- END timeline item -->--}}
                @endforeach

                <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                </li>

            </ul>

        </div>

    </div>

@endsection


@section('style')
<style>
    .line-piece .comment-container {display:none;}
</style>
@endsection
@section('js')
<script>
    $(function() {

//        $('article.point-content').readmore({
//            speed: 150,
//            moreLink: '<a href="#">展开更多</a>',
//            lessLink: '<a href="#">收起</a>'
//        });

    });
</script>
@endsection

