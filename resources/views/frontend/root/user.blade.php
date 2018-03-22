@extends('frontend.layout.layout')

@section('header_title') {{$data->name}}的主页 @endsection

@section('title') {{$data->name}}的主页 @endsection
@section('header') {{$data->name}} @endsection
@section('description','主页')
@section('breadcrumb')
@endsection

@section('content')

    <div style="display:none;">
        <input type="hidden" id="" value="{{$_encode or ''}}" readonly>
    </div>

    {{--课程s--}}
    @foreach($courses as $num => $course)
        @include('frontend.component.course')
    @endforeach

    {{ $courses->links() }}

@endsection


@section('js')
    <script>
        $(function() {

            $('article').readmore({
                speed: 150,
                moreLink: '<a href="#">展开更多</a>',
                lessLink: '<a href="#">收起</a>'
            });

            $('.course-option').on('click', '.show-menu', function () {
                var course_option = $(this).parents('.course-option');
                course_option.find('.menu-container').show();
                $(this).removeClass('show-menu').addClass('hide-menu');
                $(this).html('隐藏目录');
            });
            $('.course-option').on('click', '.hide-menu', function () {
                var course_option = $(this).parents('.course-option');
                course_option.find('.menu-container').hide();
                $(this).removeClass('hide-menu').addClass('show-menu');
                $(this).html('查看目录');
            });
        });
    </script>
@endsection
