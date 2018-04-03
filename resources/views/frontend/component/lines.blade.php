@foreach($lines as $num => $item)
<div class="item-piece line-piece item-option line-option {{$line_magnitude or 'item-plural'}}"
     data-line="{{encode($item->id)}}"
     data-point="{{encode(0)}}"
>
    <!-- BEGIN PORTLET-->
    <div class="panel-default box-default item-entity-container">

        {{--header--}}
        <div class="box-body item-title-row">
            <a href="{{url('/line/'.encode($item->id))}}">{{$item->title or ''}}</a>
        </div>

        <div class="box-body item-info-row text-muted">
            <span><a href="{{url('/u/'.encode($item->user->id))}}">{{$item->user->name or ''}}</a></span>
            <span> • {{ $item->created_at->format('n月j日 H:i') }}</span>
            <span> • 阅读 <span class="text-blue">{{ $item->visit_num }}</span> 次</span>
        </div>

        {{--description--}}
        @if(!empty($item->description))
            <div class="box-body item-description-row">
                <div class="colo-md-12 text-muted"> {!! $item->description or '' !!} </div>
            </div>
        @endif

        {{--content--}}
        @if(!empty($item->content))
            <div class="box-body item-content-row">

                <div class="media">
                    <div class="media-left">
                        <img src="{!! $item->img_tags[2][0] or '' !!}" alt="" class="media-object">
                    </div>
                    <div class="media-body">
                        <div class="clearfix">
                            <article class="colo-md-12"> {!! $item->content_show or '' !!} </article>
                        </div>
                    </div>
                </div>

            </div>
        @endif


        {{--tools--}}
        <div class="box-footer item-tools-row">

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

            <a class="margin comment-toggle" role="button" data-num="{{$item->comment_num}}">
                <i class="fa fa-commenting-o"></i> @if($item->comment_num) {{$item->comment_num}} @endif
            </a>

        </div>


        {{--comment--}}
        <div class="box-body comment-container">

            <input type="hidden" class="comments-get comments-get-default">

            <div class="box-body comment-input-container">
                <form action="" method="post" class="form-horizontal form-bordered item-comment-form">

                    {{csrf_field()}}
                    <input type="hidden" name="line_id" value="{{encode($item->id)}}" readonly>
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
@endforeach