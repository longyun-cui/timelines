
    <div class="row item-option course-option" data-course="{{encode($course->id)}}" data-content="{{encode(0)}}">
        <div class="col-md-9">
            <!-- BEGIN PORTLET-->
            <div class="box panel-default box-default">

                {{--header--}}
                <div class="box-header" style="margin:8px 0 0;border-bottom:1px solid #f4f4f4;">
                    <h3 class="box-title">
                        <a href="{{url('/course/'.encode($course->id))}}">{{$course->title or ''}}</a>
                    </h3>
                    <span>来自 <a href="{{url('/u/'.encode($course->user->id))}}">{{$course->user->name or ''}}</a></span>
                    <span class="pull-right"><a class="show-menu" style="cursor:pointer">查看目录</a></span>
                </div>

                <div class="box-body text-muted">
                    <span>阅读 <span class="text-blue">{{ $course->visit_num }}</span> 次</span>
                    <span class="pull-right">{{ $course->created_at->format('Y-n-j H:i') }}</span>
                </div>

                {{--menu--}}
                <div class="box-body menu-container" style="display:none;border-bottom:1px solid #ddd;">
                    <div class="colo-md-12 text-muted" style="margin-bottom:16px;">目录结构</div>
                    @foreach($course->contents as $content)
                        <div class="colo-md-12 box-body" style="padding:4px 10px;">
                            <a href="{{ url('course/'.encode($course->id).'?content='.encode($content->id)) }}">
                                <i class="fa fa-list-ol"></i> &nbsp; {{ $content->title or '' }}</a>
                        </div>
                    @endforeach
                </div>

                {{--description--}}
                @if(!empty($course->description))
                    <div class="box-body text-muted">
                        <div class="colo-md-12"> {!! $course->description or '' !!} </div>
                    </div>
                @endif

                {{--content--}}
                @if(!empty($course->content))
                    <div class="box-body">
                        <div class="colo-md-12"> {!! $course->content or '' !!} </div>
                    </div>
                @endif


                {{--tools--}}
                <div class="box-footer">

                    {{--点赞--}}
                    <a class="margin favor-btn" data-num="{{$course->favor_num}}" role="button">
                        @if(Auth::check())
                            @if($course->others->contains('type', 1))
                                <span class="favor-this-cancel"><i class="fa fa-thumbs-up text-red"></i>
                            @else
                                <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                            @endif
                        @else
                            <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                        @endif

                        @if($course->favor_num) {{$course->favor_num}} @endif </span>
                    </a>

                    {{--收藏--}}
                    <a class="margin collect-btn" data-num="{{$course->collect_num}}" role="button">
                        @if(Auth::check())
                            @if($course->user_id != Auth::id())
                                @if(count($course->collections))
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

                        @if($course->collect_num) {{$course->collect_num}} @endif </span>
                    </a>

                    <a class="margin _none" role="button">
                        <i class="fa fa-share"></i> @if($course->share_num) {{$course->share_num}} @endif
                    </a>

                    <a class="margin comment-toggle" role="button" data-num="{{$course->comment_num}}">
                        <i class="fa fa-commenting-o"></i> @if($course->comment_num) {{$course->comment_num}} @endif
                    </a>

                </div>


                {{--comment--}}
                <div class="box-body comment-container" style="display:none;" >

                    <input type="hidden" class="get-comments get-comments-default">

                    <div class="box-body comment-input-container">
                        <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                            {{csrf_field()}}
                            <input type="hidden" name="course_id" value="{{encode($course->id)}}" readonly>
                            <input type="hidden" name="content_id" value="{{encode(0)}}" readonly>
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

                        <div class="col-md-12" style="padding:0;margin-top:16px;">
                            <a href="{{url('/course/'.encode($course->id))}}" target="_blank">
                                <button type="button" class="btn btn-block btn-flat btn-default item-more"></button>
                            </a>
                        </div>

                    </div>

                </div>

            </div>
            <!-- END PORTLET-->
        </div>
    </div>
