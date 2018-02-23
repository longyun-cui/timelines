@extends('frontend.course.layout')

@section('title') {{$data->title}} @endsection
@section('header')
    <a href="{{url('/course/'.$data->encode_id)}}">{{$data->title}}</a>
@endsection
@section('description')
    来自 <a href="{{url('/u/'.$data->user->encode_id)}}"><b>{{ $data->user->name }}</b></a>
@endsection
@section('breadcrumb')
    <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i>首页</a></li>
    <li><a href="#"><i class="fa "></i>Here</a></li>
@endsection


@section('content')
<div style="display:none;">
    <input type="hidden" id="" value="{{$_encode or ''}}" readonly>
</div>

{{--内容--}}
<div class="row">
    <div class="col-md-12">
        <div class="box panel-default box-info">

            @if(!empty($content))
                <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                    <h3 class="box-title">{{$content->title}}</h3>
                </div>

                @if(!empty($content->description))
                    <div class="box-body">
                        <div class="colo-md-12 text-muted"> {{ $content->description or '' }} </div>
                    </div>
                @endif

                @if(!empty($content->content))
                    <div class="box-body">
                        <div class="colo-md-12"> {!! $content->content or '' !!}  </div>
                    </div>
                @endif
            @else
                <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                    <h3 class="box-title">{{$data->title}}</h3>
                </div>

                @if(!empty($data->description))
                    <div class="box-body">
                        <div class="colo-md-12 text-muted"> {{ $data->description or '' }} </div>
                    </div>
                @endif

                @if(!empty($data->content))
                    <div class="box-body">
                        <div class="colo-md-12"> {!! $data->content or '' !!}  </div>
                    </div>
                @endif
            @endif

            <div class="box-footer">
                &nbsp;
            </div>

        </div>
    </div>
</div>

@endsection


@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
