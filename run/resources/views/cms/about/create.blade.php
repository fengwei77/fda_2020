@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/about.css')) }}">
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

        .image_list {
            background-color: rgba(73, 109, 138, 0.2);
            padding: 2px;
            border-radius: 5px;
            width: 194px;
        }

        .gallery_box {
            width: 65px;
            text-align: center;
            display: inline-block;
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
                    {!! form_until($form,'images_upload') !!}
                    <label for="images" class="images-label" style="display: none;">檔案</label>
                    <div class="input-group" style="display: none;">
                       <span class="input-group-btn">
                         <a id="lfm" data-input="thumbnail" data-preview="holder" data-remove="remove"
                            class="btn btn-default">
                           <i class="far fa-file-image"></i> 選擇檔案
                         </a>
                       </span>
                        <input id="thumbnail" class="form-control col-4" type="text" name="filepath[]"
                               readonly="readonly">
                        <span class="input-group-btn" style="color: white;">
                         <a id="remove" class="btn btn-danger" style="display: none;">
                          <i class="far fa-trash-alt"></i> 刪除
                         </a>
                       </span>
                    </div>
                    <span id="holder" style="margin-top:5px;max-height:100px;"></span>
                    <div id="list" class="faa-parent  animated-group   ">
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
    <script src="{{ asset(mix('/js/about.js')) }}"></script>
    <script src="{{ asset('/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script>
        const base_url = '{{url('')}}/';
        let file_seq = 0;
        let get_files_html = '';
        let get_images_html = '';
        const load_files = [];
        const lfm_array = [];
        const load_images = '';
        const block_id = 1;
        const domain = "{{ URL::to('/cms/laravel-filemanager') }}";
        const REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
            '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';
        const upload_url = '{{ url('/cms/upload/image') }}';

        load_files.forEach(function (item, index, array) {
            lfm_array.push(item);
            add2files(item);
        });
        $('.image_list').html(get_files_html);
        if (load_images != '') {
            add2images(load_images);
        }
        $('.image_list').html(get_files_html);
        const editor_config = {
            path_absolute: "{{ URL::to('/') }}",
            branding: false,
            selector: '#content',
            language: 'zh_TW',
            height : "500",
            image_advtab: true,
            image_class_list: [
                {title: '響應式圖片', value: 'img-fluid'},
            ],
            plugins: [
                'advlist autolink lists link image charmap  preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime  table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                ' bold italic backcolor | alignleft aligncenter ' +
                ' alignright alignjustify | bullist numlist outdent indent |' +
                ' removeformat | link image',
            relative_urls: false,
            file_picker_callback: function (callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                let type = 'image' === meta.filetype ? 'Images' : 'Files',
                    url = editor_config.path_absolute + '/laravel-filemanager?editor=tinymce5&type=' + type + '&lang=' + tinymce.settings.language;
                tinymce.activeEditor.windowManager.openUrl({
                    url: url,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        };
        const tiny_01 = tinymce.init(editor_config);
        $(function () {
            let el = document.getElementById('list');
            let list = new Sortable(el,
                {
                    handle: '.btn-move',
                    animation: 150,
                    ghostClass: 'blue-background-class',
                    filter: ".f_remove_btn",
                    onEnd: function (evt) {
                        let lfm_array_temp = [];
                        // console.log(evt);
                        for (let i = 0; i < evt.to.children.length; i++) {
                            lfm_array_temp.push(evt.to.children[i].attributes[2].value);
                            console.log(i + '=>' + evt.to.children[i].attributes[2].value);
                        }
                        // evt.target.each(function(item) {
                        //     console.log(item.children.attributes[2].value);
                        // });
                        $('input[name="files"]').val(JSON.stringify(lfm_array_temp));

                    },
                    onFilter: function (evt) {
                        // console.log(evt);
                        var el = list.closest(evt.item);
                        el && el.parentNode.removeChild(el);

                        let lfm_array_temp = [];
                        for (let i = 0; i < evt.to.children.length; i++) {
                            lfm_array_temp.push(evt.to.children[i].attributes[2].value);
                            // console.log(i + '=>' + evt.to.children[i].attributes[2].value);
                        }
                        $('input[name="files"]').val(JSON.stringify(lfm_array_temp));
                    },
                });
            //檔案上傳
            // $('#lfm').filemanager('image', {prefix: domain});
            lfm('lfm', 'image', {prefix: domain});

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
            const endDate = new Date();
            endDate.setFullYear(endDate.getFullYear() + 3);
            flatpickr.localize(flatpickr_tw);
            $("#item_date").flatpickr({
                showAlways: false,
                theme: "light",
                dateFormat: "Y-m-d",
                defaultDate: "today"
            });
            $("#start_date").flatpickr({
                showAlways: false,
                theme: "light",
                dateFormat: "Y-m-d",
                defaultDate: "today"
            });
            $("#expire_date").flatpickr({
                showAlways: false,
                theme: "light",
                dateFormat: "Y-m-d",
                defaultDate: endDate
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
            let image_data = {};
            if ($('[name="images"]').val() != '') {
                if (IsValidJSONString($('[name="images"]').val())) {
                    image_data = JSON.parse($('[name="images"]').val());
                }
                for (let image_key in image_data) {
                    let img = '{{ url('/') }}/' + image_data[image_key];
                    let html = $("#image_preview").html();
                    html += ' <a data-fancybox="images" id="' + image_key + '" href=\"' + img + '\" class="fc_images"><img src=\"' + img + '\" width=\"auto\" height=\"18px\" style="    margin-top: -4px;"></a>';
                    html += '<button type="button"  id="remove_btn_' + image_key + '" to="images" class="remove_btn d-inline btn btn-block btn-outline-danger btn-xs" val="' + image_key + '" style="width:50px;\n' +
                        '    margin-left: 15px;\n' +
                        '    margin-bottom: 3px;">移除</button>';
                    $("#image_preview").html(html);

                    $('.remove_btn').click(function () {
                        let id = $(this).attr('val');
                        // console.log('del' + id);
                        swal.fire({
                            title: '確定是否要刪除?',
                            text: "",
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#747474',
                            confirmButtonText: '是的!',
                            cancelButtonText: '取消',
                        }).then(function (result) {
                            if (result.value) {
                                delete image_data;
                                $('#' + image_key).remove();
                                $('#remove_btn').remove();
                                // console.log('delete');

                            }
                        })
                    });

                }
            }
            $('.image_progress').html(
                '<div id="product_image_progress" class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">\n' +
                '                    <span class="sr-only">0% Complete (warning)</span>\n' +
                '                  </div>'
            );
            $('#images_upload').change(function () {
                $('#product_image_progress').attr('aria-valuenow', 0);
                $('#product_image_progress').css('width', '0%');
                upload(upload_url, 'images_upload');
            });
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
            $('.productForm').submit(function () {
                $.LoadingOverlay("show", {
                    image: "",
                    fontawesome: "fas fa-circle-notch fa-spin"
                });
            });
        });

        var lfm = function(id, type, options) {
            let button = document.getElementById(id);

            button.addEventListener('click', function () {
                var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
                var target_input = document.getElementById(button.getAttribute('data-input'));
                var target_preview = document.getElementById(button.getAttribute('data-preview'));

                window.open(route_prefix + '?type=' + type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = function (items) {
                    var file_path = items.map(function (item) {
                        return item.url;
                    }).join(',');

                    // set the value of the desired input to image url
                    target_input.value = file_path;
                    target_input.dispatchEvent(new Event('change'));

                    // clear previous preview
                    target_preview.innerHtml = '';

                    // set or change the preview image src
                    items.forEach(function (item) {
                        let img = document.createElement('img')
                        img.setAttribute('style', 'height: 5rem')
                        img.setAttribute('src', item.thumb_url)
                        target_preview.appendChild(img);
                    });

                    // trigger change event
                    target_preview.dispatchEvent(new Event('change'));
                };
            });
        };
    </script>
@endsection
