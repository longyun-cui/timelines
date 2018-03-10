
    <div class="row item-option course-option" data-id="{{encode($data->id)}}">
        <div class="col-md-9">
            <!-- BEGIN PORTLET-->
            <div class="box panel-default box-default">

                <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                    <h3 class="box-title">
                        <a href="{{url('/course/'.encode($data->id))}}">{{$data->title or ''}}</a>
                    </h3>
                    <span>来自 <a href="{{url('/u/'.encode($data->user->id))}}">{{$data->user->name or ''}}</a></span>
                    <span class="pull-right"><a class="show-menu" style="cursor:pointer">查看目录</a></span>
                </div>

                <div class="box-body text-muted">
                    <span>阅读 <span class="text-blue">{{ $data->visit_num }}</span> 次</span>
                    <span class="pull-right">{{ $data->created_at->format('Y-n-j H:i') }}</span>
                </div>

                <div class="box-body menu-container" style="display:none;border-bottom:1px solid #ddd;">
                    <div class="colo-md-12 text-muted" style="margin-bottom:16px;">目录结构</div>
                    @foreach($data->contents as $content)
                        <div class="colo-md-12 box-body" style="padding:4px 10px;">
                            <a href="{{ url('course/'.encode($data->id).'?content='.encode($content->id)) }}">
                                <i class="fa fa-list-ol"></i> &nbsp; {{ $content->title or '' }}</a>
                        </div>
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

                    {{--收藏--}}
                    <a class="margin">
                        @if(Auth::check())
                            @if($data->user_id != Auth::id())
                                @if(count($data->collections))
                                    <span class="margin collect-this-cancel"><i class="fa fa-heart text-red"></i>
                                @else
                                    <span class="margin collect-this"><i class="fa fa-heart-o"></i>
                                @endif
                            @else
                                <span class=""><i class="fa fa-heart-o"></i>
                            @endif
                        @else
                            <span class="margin collect-this"><i class="fa fa-heart-o"></i>
                        @endif

                        @if($data->collect_num) {{$data->collect_num}} @endif </span>
                    </a>

                    <a class="margin"><i class="fa fa-share"></i> @if($data->share_num) {{$data->share_num}} @endif</a>

                    <a class="margin comment-toggle"><i class="fa fa-commenting-o"></i> @if($data->comment_num) {{$data->comment_num}} @endif</a>

                    {{--点赞--}}
                    <a class="margin">
                        @if(Auth::check())
                            @if($data->others->contains('type', 1))
                                <span class="favor-this-cancel"><i class="fa fa-thumbs-up text-red"></i>
                            @else
                                <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                            @endif
                        @else
                            <span class="favor-this"><i class="fa fa-thumbs-o-up"></i>
                        @endif

                        @if($data->favor_num) {{$data->favor_num}} @endif </span>
                    </a>
                </div>

            </div>
            <!-- END PORTLET-->
        </div>
    </div>
