<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tfdacos 管理系統</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('/images/favicon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset(mix('/css/login.css')) }}">
</head>
<body class="hold-transition login-page">


<div class="login-box">
    <div class="login-logo">

        <a href="#"> Tfdacos 管理平台</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">請輸入你的帳號密碼</p>
            <form action="{{ url('/cms/login/checker') }}"  method="post">
                {{ csrf_field() }}
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Account" name="account">
                    <div class="input-group-append input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <div class="input-group-append input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">

                        @if($errors->any())
                            <span style="color: red;">{{$errors->first()}}</span>
                        @endif
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" id=" " class="btn btn-primary btn-block btn-flat">登入</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
<script src="{{ asset(mix('js/login.js')) }}"></script>
</body>
</html>
