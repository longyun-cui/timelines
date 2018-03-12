
    <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
        <h3 class="box-title">{{$content->title}}</h3>
    </div>
    <div class="box-body text-muted">
        <span>阅读 <span class="text-blue">{{ $content->visit_num }}</span> 次</span>
        <span class="pull-right">{{ $content->created_at->format('Y-n-j H:i') }}</span>
    </div>

    @if(!empty($content->description))
        <div class="box-body text-muted">
            <div class="colo-md-12"> {!! $content->description or '' !!}  </div>
        </div>
    @endif

    @if(!empty($content->content))
        <div class="box-body">
            <div class="colo-md-12"> {!! $content->content or '' !!}  </div>
        </div>
    @endif

