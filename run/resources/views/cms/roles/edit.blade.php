@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/role.css')) }}">
    <style>
        input[type="checkbox"] {
            width: 1.2em; /*Desired width*/
            height: 1.2em; /*Desired height*/
            top: 3px;
            position: relative;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{$page_title}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{$page_title}}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card">
                <div class="card-body">
                    {{ Form::model($role, array('route' => array('cms.roles.update', $role->id), 'method' => 'PUT')) }}

                    <div class="form-group">
                        {{ Form::label('name', '名稱', array('class' => 'h5')) }}
                        {{ Form::text('name', null, array('class' => 'form-control')) }}
                    </div>

                    <h5><b>權限設定</b></h5>
                    @foreach ($permissions as $permission)

                        <div class="checkbox_block">
                        {{Form::checkbox('permissions[]',  $permission->id, $role->permissions , ['id'=> $permission->name ]) }}
                            {{Form::label($permission->name, ucfirst($permission->name)) }}
                        </div>

                    @endforeach
                    <br>
                    {{ Form::submit('送出', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
                <!-- /.card-body -->
            {{--<div class="card-footer">--}}
            {{--    Footer--}}
            {{--</div>--}}
            <!-- /.card-footer-->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>

@endsection

@section('scripts')
    <script src="{{ asset(mix('/js/role.js')) }}"></script>
    <script type="text/javascript">
        $(function () {

            var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

            @if ($message = Session::get('success'))
                toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr.success('', '{{ $message }}');
            @endif
                @if ($message = Session::get('failed'))
                toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "2000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr.error('', '{{ $message }}');
            @endif
        });
    </script>
@endsection
