
    <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
        <h3 class="box-title">{{$course->title}}</h3>
    </div>
    <div class="box-body text-muted">
        <span>阅读 <span class="text-blue">{{ $course->visit_num }}</span> 次</span>
        <span class="pull-right">{{ $course->created_at->format('Y-n-j H:i') }}</span>
    </div>

    @if(!empty($course->description))
        <div class="box-body text-muted">
            <div class="colo-md-12"> {!! $course->description or '' !!}  </div>
        </div>
    @endif

    @if(!empty($course->content))
        <div class="box-body">
            <div class="colo-md-12"> {!! $course->content or '' !!}  </div>
        </div>
    @endif
