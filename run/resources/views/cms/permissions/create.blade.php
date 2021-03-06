@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/permission.css')) }}">
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
                    {{ Form::open(array('url' => 'cms/permissions/store')) }}

                    <div class="form-group">
                        {{ Form::label('name', '名稱', array('class' => 'h5')) }}
                        {{ Form::text('name', '', array('class' => 'form-control')) }}
                    </div>
                    <br>
                    @if(!$roles->isEmpty())
                        <h4>分配給角色</h4>

                        @foreach ($roles as $role)
                            @if($role->name != 'isAdmin')
                                <div class="checkbox_block">
                                    {{ Form::checkbox('roles[]',  $role->id ,'' ,['id'=> $role->name ]) }}
                                    {{ Form::label($role->name, ucfirst($role->name)) }}
                                </div>
                            @endif
                        @endforeach
                    @endif
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
    <script src="{{ asset(mix('/js/permission.js')) }}"></script>
    <script type="text/javascript">
        //custom
        $(function () {
            var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';
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
