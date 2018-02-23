@extends('frontend.layout.layout')

@section('title') {{$data->name}}的主页 @endsection
@section('header') {{$data->name}} @endsection
@section('description','主页')
@section('breadcrumb')
@endsection

@section('content')
<div style="display:none;">
    <input type="hidden" id="" value="{{$_encode or ''}}" readonly>
</div>

{{--课程--}}
@foreach($courses as $num => $course)
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PORTLET-->
            <div class="box panel-default
                @if($loop->index % 7 == 0) box-info
                @elseif($loop->index % 7 == 1) box-danger
                @elseif($loop->index % 7 == 2) box-success
                @elseif($loop->index % 7 == 3) box-default
                @elseif($loop->index % 7 == 4) box-warning
                @elseif($loop->index % 7 == 5) box-primary
                @elseif($loop->index % 7 == 6) box-danger
                @endif
            ">

                <div class="box-header with-border panel-heading" style="margin:16px 0 8px;">
                    <h3 class="box-title"><a href="{{url('/course/'.encode($course->id))}}">{{$course->title or ''}}</a></h3>
                </div>

                @if(!empty($course->description))
                    <div class="box-body">
                        <div class="colo-md-12 text-muted"> {{ $course->description or '' }} </div>
                    </div>
                @endif

                @if(!empty($course->content))
                    <div class="box-body">
                        <div class="colo-md-12"> {!! $course->content or '' !!}  </div>
                    </div>
                @endif

                <div class="box-footer">
                    &nbsp;
                </div>

            </div>
            <!-- END PORTLET-->
        </div>
    </div>
@endforeach

{{ $courses->links() }}

@endsection


@section('js')
    <script>
        $(function() {
        });
    </script>
@endsection
