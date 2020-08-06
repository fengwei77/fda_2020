@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/newsletter.css')) }}">
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
                    {!! form_start($form) !!}

                    <a id="holder" data-fancybox="gallery" href="" style="margin-top:5px;max-height:100px;"></a>
                    <div id="list" class="faa-parent  animated-hover group image_list ">
                    </div>
                    {!! form_end($form, $renderRest = true)!!}

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
    <script>
        const base_url = '{{url('')}}/';
        let file_seq = 0;
        let get_files_html = '';
        let get_images_html = '';
        const load_files = [];
        const lfm_array = [];
        const block_id = 1;
        const domain = "{{ URL::to('/cms/laravel-filemanager') }}";
        const REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
            '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';
        const upload_url = '{{ url('/cms/upload/image') }}';
    </script>
    <script src="{{ asset(mix('/js/newsletter.js')) }}"></script>
    <script src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script>
        load_files.forEach(function (item, index, array) {
            lfm_array.push(item);
            add2files(item);
        });
        const editor_config = {
            path_absolute: "{{ URL::to('/') }}",
            branding: false,
            selector: '#content',
            language: 'zh_TW',
            height : "400",
            plugins: [
                'link image charmap preview ',
                'code fullscreen',
                'table paste code '
            ],
            toolbar: 'undo redo | formatselect | ' +
                ' bold italic backcolor | alignleft aligncenter ' +
                ' alignright alignjustify | bullist numlist outdent indent |' +
                ' removeformat | image',
            toolbar: 'undo redo | formatselect | ' +
                ' bold italic backcolor table | removeformat | link image',
            relative_urls: false,
            file_picker_callback: function (callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                let type = 'image' === meta.filetype ? 'Images' : 'Files',
                    url = editor_config.path_absolute + '/laravel-filemanager?editor=tinymce5&type=' + type + '&lang=' + tinymce.settings.language;

                tinymce.activeEditor.windowManager.openUrl({
                    url: url,
                    title: 'Filemanager',
                    width: x * 0.85,
                    height: y * 0.9,
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };
        const tiny_01 = tinymce.init(editor_config);
        $(function () {
            let el = document.getElementById('list');
            //檔案上傳
            $('#lfm').filemanager('image', {prefix: domain});
            $("input[name='filepath[]']").change(function () {
                let file_path_val = $("input[name='filepath[]']").map(function () {
                    return $(this).val();
                }).get();
                file_path_val = file_path_val[0].replace(base_url, '');
                lfm_array.push(file_path_val);
                $('input[name="files"]').val(JSON.stringify(lfm_array));
                // console.log(file_path_val);
            });
            //開始日期
            flatpickr.localize(flatpickr_tw);
            $("#scheduled_time").flatpickr({
                showAlways: true,
                enableTime: true,
                dateFormat: "Y-m-d",
                defaultDate: "today"
            });
            $('.flatpickr-input').wrap(function () {
                return '<div class="input-group dt_box" />';
            });
            $('.dt_box').prepend(' <div class="input-group-prepend">\n' +
                '<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>\n' +
                '</div>');

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
            //送出
            $('.newsletterForm').submit(function () {
                $.LoadingOverlay("show", {
                    image: "",
                    fontawesome: "fas fa-circle-notch fa-spin"
                });
            });
        });
    </script>
@endsection
