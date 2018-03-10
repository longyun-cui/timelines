
    <div class="colo-md-12 box-body comment-option comment-piece" style="">
        <div class="box-body" style="padding:4px 0">

            <a href="{{url('/u/'.encode($comment->user->id))}}" target="_blank">{{$comment->user->name}}</a>

            <span class="pull-right text-muted disabled">{{ $comment->created_at->format('n月j日 H:i') }}</span>
        </div>
        <div class="box-body" style="padding:0;">

            <p> {{ $comment->content }} </p>

        </div>
    </div>

