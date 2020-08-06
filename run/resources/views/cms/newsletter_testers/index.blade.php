@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/newsletter_tester.css')) }}">
    <style>
        .file_wrap {
            height: calc(2.25rem + 8px);
        }

        .nopadding {
            padding: 0 !important;
            margin: 0 !important;
        }

        .text-warning {
            background-color: #0c5460;
        }

        #lfm {
            margin-bottom: 5px;
            padding: 10px;
            width: 195px;
        }

        #thumbnail {
            height: 34px;
            margin-bottom: 10px;
            display: none;
        }

        .load_image_box {
            background-color: #eeeeee;
            margin: 4px;
            padding: 2px;
            border: 1px solid;
            border-color: rgba(209, 19, 16, 0.81);
            border-radius: 5px;
            display: block;
            width: 160px;
        }

        .load_file_box {
            background-color: #eeeeee;
            margin: 4px;
            padding: 2px;
            border: 1px solid;
            border-color: rgba(209, 19, 16, 0.81);
            border-radius: 5px;
            display: block;
            width: 180px;
        }

        .btn-move {
            display: inline-block;
            text-align: center;
            width: 30px;
        }

        .list_catch, .list_sharp {
            text-align: center;
            font-size: 8px;
        }

        .btn-move i {
            /*display: none;*/
            color: #4d4d4d;

        }

        .btn-move:hover i {
            color: black;
            display: inline-block;
            font-size: 18px;
            cursor: move;
        }
        .tagify__tag>div>* {
            overflow: visible;
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
                    {{ Form::open(array('url' => 'cms/newsletter_testers/store')) }}

                    <div class="form-group">
                        {{ Form::label('emails', '信箱', array('class' => 'h5')) }}
                        {{ Form::text('emails',  implode(',',array_column($newsletter_testers, 'email')) , array('class' => 'form-control', 'placeholder'=> '輸入收件者信箱')) }}
                    </div>

                    {{ Form::button('返回', array('class' => 'btn btn-success', 'id' => 'go_back')) }}

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
    <script src="{{ asset(mix('/js/newsletter_tester.js')) }}"></script>
    <script>

        var input = document.querySelector('input[name=emails]'),
            tagify = new Tagify(input, {
                callbacks: {
                    add: onAddTag,  // callback when adding a tag
                    remove: onRemoveTag   // callback when removing a tag
                }
            });

        $(function () {
            //返回
            $('#go_back').click(function () {
                location.href = '{{ url('cms/newsletters') }}';
            });
            //送出
            $('.newsletter_tester_form').submit(function () {
                $.LoadingOverlay("show", {
                    image: "",
                    fontawesome: "fas fa-circle-notch fa-spin"
                });
            });
        });


        function onAddTag() {
            console.log("onInput");
        }

        function onRemoveTag() {

        }
    </script>
@endsection
