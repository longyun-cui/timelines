<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/ico" href="{{ url('favicon.ico') }}">
    <link rel="shortcut icon" type="image/png" href="{{ url('favicon.png') }}">
    <link rel="icon" sizes="16x16 32x32 64x64" href="{{ url('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="196x196" href="{{ url('favicon.png') }}">
    <title>@yield('title')</title>
    <meta name="_token" content="{{ csrf_token() }}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/AdminLTE/bootstrap/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">--}}
    <!-- Font Awesome -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">--}}
    <link href="https://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Ionicons -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">--}}
    <link href="https://cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/AdminLTE.min.css">
    {{--<link href="https://cdn.bootcss.com/admin-lte/2.3.11/css/AdminLTE.min.css" rel="stylesheet">--}}
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="/AdminLTE/dist/css/skins/skin-blue.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    {{--<!--[if lt IE 9]>--}}
    {{--<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>--}}
    {{--<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>--}}
    {{--<![endif]-->--}}
    {{--<link href="https://cdn.bootcss.com/bootstrap-modal/2.2.6/css/bootstrap-modal.min.css" rel="stylesheet">--}}

    <link href="https://cdn.bootcss.com/bootstrap-fileinput/4.4.3/css/fileinput.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('AdminLTE/plugins/datatables/dataTables.bootstrap.css')}}">

    <link href="https://cdn.bootcss.com/iCheck/1.0.2/skins/all.css" rel="stylesheet">

    <script src="https://cdn.bootcss.com/moment.js/2.19.0/moment.min.js"></script>

    <link href="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

    <link href="https://cdn.bootcss.com/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/common.css')}}">
    <link rel="stylesheet" href="{{asset('css/home/index.css')}}">

    @yield('style')

</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{url('/home')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Me</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>时间线后台</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- Add Menu -->
                    <li class="dropdown tasks-menu add-menu">
                        <!-- Menu toggle button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-plus"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><a href="{{url('/home/course/create')}}"><i class="fa fa-circle-o text-red"></i>添加课程</a></li>
                            <li class="footer"><a href="#">See All Messages</a></li>
                        </ul>
                    </li>

                    <!-- Messages: style can be found in dropdown.less-->
                    <li class="dropdown messages-menu" style="display:none;">
                        <!-- Menu toggle button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">4</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 4 messages</li>
                            <li>
                                <!-- inner menu: contains the messages -->
                                <ul class="menu">
                                    <li><!-- start message -->
                                        <a href="#">
                                            <div class="pull-left">
                                                <!-- User Image -->
                                                <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                            </div>
                                            <!-- Message title and timestamp -->
                                            <h4>
                                                Support Team
                                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                            </h4>
                                            <!-- The message -->
                                            <p>Why not buy a new awesome theme?</p>
                                        </a>
                                    </li>
                                    <!-- end message -->
                                </ul>
                                <!-- /.menu -->
                            </li>
                            <li class="footer"><a href="#">See All Messages</a></li>
                        </ul>
                    </li>

                    <!-- Notifications Menu -->
                    <li class="dropdown notifications-menu _none" style="display:none;">
                        <!-- Menu toggle button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">{{$notification_count or ''}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">你有 {{$notification_count or ''}} 未读消息</li>
                            <li>
                                <!-- Inner Menu: contains the notifications -->
                                <ul class="menu">
                                    <li><!-- start notification -->
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                    <!-- end notification -->
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>

                    <!-- Tasks Menu -->
                    <li class="dropdown tasks-menu" style="display:none;">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 9 tasks</li>
                            <li>
                                <!-- Inner menu: contains the tasks -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                Design some buttons
                                                <small class="pull-right">20%</small>
                                            </h3>
                                            <!-- The progress bar -->
                                            <div class="progress xs">
                                                <!-- Change the css width attribute to simulate progress -->
                                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="sr-only">20% Complete</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="#">View all tasks</a>
                            </li>
                        </ul>
                    </li>

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                <p>
                                    {{Auth::user()->name}}
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="/home/info/index" class="btn btn-default btn-flat">个人资料</a>
                                </div>
                                <div class="pull-right">
                                    <a href="/logout" class="btn btn-default btn-flat user-logout">退出</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Control Sidebar Toggle Button -->
                    <li style="display:none;">
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>



    {{--<!-- Left side column. contains the logo and sidebar -->--}}
    <aside class="main-sidebar">

        {{--<!-- sidebar: style can be found in sidebar.less -->--}}
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- search form (Optional) -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">

                <li class="header">时间线列表</li>

                <li class="treeview {{$menu_line_list or ''}}">
                    <a href="{{url('/home/line/list')}}"><i class="fa fa-table text-green"></i> <span>时间线列表</span></a>
                </li>

                <li class="treeview {{$menu_line_create or ''}}">
                    <a href="{{url('/home/line/create')}}"><i class="fa fa-plus text-green"></i> <span>添加时间线</span></a>
                </li>



                <li class="header">其他</li>

                <li class="treeview {{$menu_collect_line or ''}}">
                    <a href="{{url('/home/collect/line/list')}}"><i class="fa fa-heart text-red"></i> <span>收藏「时间线」</span></a>
                </li>

                <li class="treeview {{$menu_favor_line or ''}}">
                    <a href="{{url('/home/favor/line/list')}}"><i class="fa fa-thumbs-up text-red"></i> <span>点赞「时间线」</span></a>
                </li>

                <li class="treeview {{$menu_collect_point or ''}}">
                    <a href="{{url('/home/collect/point/list')}}"><i class="fa fa-heart text-blue"></i> <span>收藏内容</span></a>
                </li>

                <li class="treeview {{$menu_favor_point or ''}}">
                    <a href="{{url('/home/favor/point/list')}}"><i class="fa fa-thumbs-up text-blue"></i> <span>点赞内容</span></a>
                </li>



                <li class="header"><i class="fa fa-envelope"></i></li>

                <li class="treeview {{$menu_notification_comment or ''}}">
                    <a href="{{url('/home/notification/comment')}}">
                        <i class="fa fa-bell"></i>消息
                        <span class="pull-right-container">
                            <small class="label pull-right bg-red">{{$notification_count or ''}}</small>
                            <small class="label pull-right bg-blue _none">17</small>
                        </span>
                    </a>
                </li>

                <li class="treeview {{$menu_notification_favor or ''}} _none">
                    <a href="{{url('/home/notification/favor')}}"><i class="fa fa-dot-circle-o"></i>点赞</a>
                </li>

                <li class="treeview {{$menu_notification_comment or ''}} {{$menu_notification_favor or ''}} _none">
                    <a href="">
                        <i class="fa fa-envelope"></i> <span>消息</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{$menu_notification_comment or ''}}">
                            <a href="{{url('/home/notification/comment')}}"><i class="fa fa-dot-circle-o"></i>评论</a>
                        </li>
                        <li class="{{$menu_notification_favor or ''}}">
                            <a href="{{url('/home/notification/favor')}}"><i class="fa fa-dot-circle-o"></i>点赞</a>
                        </li>
                    </ul>
                </li>
                

                <li class="header">课程站</li>

                <li class="treeview">
                    <a href="{{url('/')}}" target="_blank"><i class="fa fa-cube text-default"></i> <span>平台主页</span></a>
                </li>


            </ul>
            <!-- /.sidebar-menu -->
        </section>
        {{--<!-- /.sidebar -->--}}
    </aside>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('header')
                <small>@yield('description')</small>
            </h1>
            <ol class="breadcrumb">
                @yield('breadcrumb')
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content') {{--Your Page Content Here--}}
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    {{--<!-- Main Footer -->--}}
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            Anything you want
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 上海如哉网络科技有限公司 2017 <a href="#">Company</a>.</strong> All rights reserved. 沪ICP备17052782号-1
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

{{--<!-- jQuery 2.2.3 -->--}}
<script src="/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
{{--<!-- Bootstrap 3.3.6 -->--}}
<script src="/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
{{--<!-- AdminLTE App -->--}}
<script src="/AdminLTE/dist/js/app.min.js"></script>

<script src="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.js"></script>

{{--<script src="https://cdn.bootcss.com/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js"></script>--}}

<script src="https://cdn.bootcss.com/layer/3.0.3/layer.min.js"></script>

<script src="https://cdn.bootcss.com/bootstrap-fileinput/4.4.3/js/fileinput.min.js"></script>

<script src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.min.js"></script>

<script src="{{asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('AdminLTE/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

<script src="https://cdn.bootcss.com/iCheck/1.0.2/icheck.min.js"></script>

<script src="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<script src="https://cdn.bootcss.com/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>

<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>

<script>

</script>


<script src="{{asset('js/common.js')}}"></script>
<script src="{{asset('js/home/index.js')}}"></script>

@yield('js')

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
</body>
</html>
