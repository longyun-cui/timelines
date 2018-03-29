<div class="item-piece item-option point-option {{$point_magnitude or 'item-singular'}}"
     data-line="{{encode($data->line->id)}}"
     data-point="{{encode($data->id)}}"
>
    <!-- BEGIN PORTLET-->
    <div class="panel-default box-default item-entity-container">

        {{--header--}}
        <div class="box-body item-title-row">
            <a href="{{url('/line/'.encode($data->id))}}">{{$data->title or ''}}</a>
        </div>

        <div class="box-body item-info-row text-muted">
            <span><a href="{{url('/u/'.encode($data->user->id))}}">{{$data->user->name or ''}}</a></span>
            <span> • {{ $data->created_at->format('n月j日 H:i') }}</span>
            <span> • 阅读 <span class="text-blue">{{ $data->visit_num }}</span> 次</span>
        </div>

        {{--description--}}
        @if(!empty($data->description))
            <div class="box-body item-description-row">
                <div class="colo-md-12 text-muted"> {!! $data->description or '' !!} </div>
            </div>
        @endif

        {{--content--}}
        @if(!empty($data->content))
            <div class="box-body item-content-row">
                <article class="colo-md-12"> {!! $data->content or '' !!} </article>
            </div>
        @endif


        {{--tools--}}
        <div class="box-footer item-tools-row">

            {{--点赞--}}
            <a class="margin favor-btn" data-num="{{$data->favor_num}}" role="button">
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

            {{--收藏--}}
            <a class="margin collect-btn" data-num="{{$data->collect_num}}" role="button">
                @if(Auth::check())
                    @if($data->user_id != Auth::id())
                        @if(count($data->collections))
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

                @if($data->collect_num) {{$data->collect_num}} @endif </span>
            </a>

            <a class="margin _none" role="button">
                <i class="fa fa-share"></i> @if($data->share_num) {{$data->share_num}} @endif
            </a>

            <a class="margin comment-toggle" role="button" data-num="{{$data->comment_num}}">
                <i class="fa fa-commenting-o"></i> @if($data->comment_num) {{$data->comment_num}} @endif
            </a>

        </div>


        {{--comment--}}
        <div class="box-body comment-container">

            <input type="hidden" class="comments-get comments-get-default">

            <div class="box-body comment-input-container">
                <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                    {{csrf_field()}}
                    <input type="hidden" name="line_id" value="{{encode($data->id)}}" readonly>
                    <input type="hidden" name="point_id" value="{{encode(0)}}" readonly>
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
                    <button type="button" class="btn btn-block btn-flat btn-more item-more comments-more"></button>
                </div>

            </div>

        </div>

    </div>
    <!-- END PORTLET-->
</div>