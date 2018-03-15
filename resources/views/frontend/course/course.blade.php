@extends('frontend.course.layout')

@section('title') {{$course->title}} @endsection
@section('header','')
@section('description','')

@section('header_title') {{$course->title}} @endsection


@section('content')
<div style="display:none;">
    <input type="hidden" id="" value="{{$_encode or ''}}" readonly>
</div>

{{--内容--}}
<div class="row item-option course-option item-piece" data-course="{{$course->encode_id or encode(0)}}" data-content="{{$content->encode_id or encode(0)}}">
    <div class="col-md-9">
        <div class="box panel-default box-default">

            @if(!empty($content))
                @include('frontend.course.component.content')
            @else
                @include('frontend.course.component.course')
            @endif

            {{--tools--}}
            <div class="box-footer">

                {{--点赞--}}
                <a class="margin favor-btn" data-num="{{$item->favor_num}}" role="button">
                    @if(Auth::check())
                        @if($item->others->contains('type', 1))
                            <span class="favor-this-cancel"><i class="fa fa-thumbs-up text-red"></i>
                        @else
                            <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                        @endif
                    @else
                        <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                    @endif

                    @if($item->favor_num) {{$item->favor_num}} @endif </span>
                </a>

                {{--收藏--}}
                <a class="margin collect-btn" data-num="{{$item->collect_num}}" role="button">
                    @if(Auth::check())
                        @if($item->user_id != Auth::id())
                            @if(count($item->collections))
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

                    @if($item->collect_num) {{$item->collect_num}} @endif </span>
                </a>

                <a class="margin _none" role="button">
                    <i class="fa fa-share"></i> @if($item->share_num) {{$item->share_num}} @endif
                </a>

                <a class="margin comment-btn" role="button" data-num="{{$item->comment_num}}">
                    <i class="fa fa-commenting-o"></i> @if($item->comment_num) {{$item->comment_num}} @endif
                </a>

            </div>

            {{--comment--}}
            <div class="box-body comment-container">

                <input type="hidden" class="get-comments get-comments-default">

                {{--添加评论--}}
                <div class="box-body comment-input-container">
                    <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                        {{csrf_field()}}
                        <input type="hidden" name="course_id" value="{{$course->encode_id or encode(0)}}" readonly>
                        <input type="hidden" name="content_id" value="{{$content->encode_id or encode(0)}}" readonly>
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

                    <div class="col-md-12 more-box" style="margin-top:16px;">
                        <button type="button" class="btn btn-block btn-flat btn-default comments-more"></button>
                    </div>

                </div>

            </div>


        </div>
    </div>
</div>

@endsection


@section('js')
    <script>
        $(function() {

            fold();
            $(".get-comments-default").click();

        });
    </script>
@endsection
