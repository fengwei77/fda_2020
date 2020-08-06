<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <title>  @yield('title') </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('/images/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset(mix('/css/lte_plugins.css')) }}">
    @yield('style')
    <style>
        [class*=sidebar-dark-] .nav-treeview .active .nav-link,
        [class*=sidebar-dark-] .nav-treeview .active .nav-link:focus,
        [class*=sidebar-dark-] .nav-treeview .active .nav-link:hover {
            background-color: rgba(255,255,255,.9);
            color: #343a40;
        }

        [class*=sidebar-dark-] .nav-sidebar>.nav-item.menu-open>.nav-link,
        [class*=sidebar-dark-] .nav-sidebar>.nav-item:hover>.nav-link,
        [class*=sidebar-dark-] .nav-sidebar>.nav-item>.nav-link:focus
        {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini text-sm">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-info">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            @role('Admin') {{-- Laravel-permission blade 辅助函数 --}}
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-btn fa-unlock"></i> Admin</a>
            </li>
            @endrole

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/cms/login/logout') }}" alt="登出" title="登出">
                    <i class="fa fa-sign-out-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar elevation-4 sidebar-light-info">
        <!-- Brand Logo -->
        <a href="{{ url('cms/dashboard') }}" class="brand-link">
            <img src="{{ asset('images/logo.png') }}"
                 alt="CMS"
                 class="brand-image img-circle elevation-3"
                 style="opacity: .8;width:32px;height:32px;background-color: #ffffff">
            <span class="brand-text font-weight-light">抽獎管理平台</span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    {{--                    <a href="#" class="d-block">{{ Auth::user()->account }}</a>--}}
                </div>
            </div>
            <nav class="mt-2">
                {!! $menu->asUl( ["class"=>"main_menu nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact", "data-widget"=>"treeview", "role"=>"menu", "data-accordion"=>"false"], ['class' => 'nav nav-treeview']) !!}
            </nav>
            <!-- Sidebar Menu -->
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
@yield('content')
<!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> {{ config('app.website_version') }}
        </div>
        Powered by <b>Laravel {{ App::VERSION() }} </b> & Copyright &copy; {{ now()->year }} in <strong> <img
                src="{!! url('images/logo.png')  !!} " style="opacity: .8;width:14px;height:14px;margin-bottom: 3px;"> </strong>
        All rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>

    @if(Session::has('flash_message'))
        <div class="container">
            <div class="alert alert-success"><em> {!! session('flash_message') !!}</em>
            </div>
        </div>
    @endif

<!-- /.control-sidebar -->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include ('cms.errors.list') {{-- 引入错误文件 --}}
        </div>
    </div>
</div>
<!-- ./wrapper -->

@php
    if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false)) {
      //is IE 11 or below
     echo '{{--es6--}}';
     echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/es6-promise/4.1.1/es6-promise.min.js"></script>';
     echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/es6-promise/4.1.1/es6-promise.auto.min.js"></script>';
     echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-polyfill/7.8.7/polyfill.min.js"></script>';
     echo '<script src="https://code.jquery.com/jquery-migrate-3.1.0.min.js" integrity="sha256-ycJeXbll9m7dHKeaPbXBkZH8BuP99SmPm/8q5O+SbBc="  crossorigin="anonymous"></script>';
    }
@endphp
<script src="{{ asset(mix('/js/lte_plugins.js')) }}"></script>
@yield('scripts')
</body>
</html>
