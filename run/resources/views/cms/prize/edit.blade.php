@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/prize.css')) }}">
    <style>
        .file_wrap {
            height: calc(2.25rem + 8px);
        }

        .nopadding {
            padding: 0 !important;
            margin: 0 !important;
        }
        .info_txt{
            color: #b6513a;
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
                    {!! form_until($form,'uuid') !!}
                    <div class="form-group">
                        <label for="username" class=" required">抽獎資料來源 (<span class="info_txt">不可修改</span>)</label>

                        <input class="form-control  col-4"  type="text" value=" {{$query_result['lottery_list'] == 0 ? '遊戲玩家': '發票登錄'}}"  disabled>
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
    <script src="{{ asset(mix('/js/prize.js')) }}"></script>
    <script type="text/javascript">
        var url = '{{url('')}}/';
        var block_id = 1;
        var customElement = $("<div>", {
            "css": {
                "border": "4px dashed gold",
                "font-size": "40px",
                "text-align": "center",
                "padding": "10px"
            },
            "class": "your-custom-class",
            "text": "Custom!"
        });
        //custom
        $(function () {
            var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

            //開始日期
            flatpickr.localize(flatpickr_tw);
            $("#start_date").flatpickr({
                showAlways: false,
                theme: "light",
            });
            $("#expire_date").flatpickr({
                showAlways: false,
                theme: "light"
            });

            $('.flatpickr-input').wrap(function () {
                return '<div class="input-group dt_box" />';
            });
            $('.dt_box').prepend(' <div class="input-group-prepend">\n' +
                '<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>\n' +
                '</div>');

            let image_data = {};
            if ($('[name="images"]').val() != '') {
                if (IsValidJSONString($('[name="images"]').val())) {
                    image_data = JSON.parse($('[name="images"]').val());
                }
                console.log(image_data['url']);
                let img = image_data['url'];
                let html = ' <a data-fancybox="gallery" id="pv_images_upload" href=\"' + img + '\" class="fc_images"><img src=\"' + img + '\" width=\"auto\" height=\"80px\" style="    margin-top: -4px;"></a>';
                html += '<button type="button"  id="remove_btn_pv_images_upload" to="images" class="remove_btn d-inline btn btn-block btn-outline-danger btn-xs" val="images_upload" style="width:50px;\n' +
                    '    margin-left: 15px;\n' +
                    '    margin-bottom: 3px;">移除</button>';
                $(".image_progress").after(html);

                $('.remove_btn').click(function () {
                    let id = $(this).attr('val');
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
                            $('#pv_' + id).remove();
                            $('#remove_btn_pv_images_upload').remove();
                            $('[name="images"]').val('');
                        }
                    })
                });
            }


            $('.image_progress').html(
                '<div id="prize_image_progress" class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">\n' +
                '                    <span class="sr-only">0% Complete (warning)</span>\n' +
                '                  </div>'
            );

            $('#images_upload').change(function () {
                $('#prize_image_progress').attr('aria-valuenow', 0);
                $('#prize_image_progress').css('width', '0%');
                upload('images_upload');
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
            $('.PrizeForm').submit(function () {
                $.LoadingOverlay("show", {
                    image: "",
                    fontawesome: "fas fa-circle-notch fa-spin"
                });
            });

        });

        function upload(file_name, type = '') {
            const config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function (progressEvent) {
                    var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    $('#prize_image_progress').attr('aria-valuenow', percentCompleted);
                    $('#prize_image_progress').css('width', percentCompleted + '%');
                }
            }

            let formData = new FormData();
            formData.append('files', document.getElementById(file_name).files[0]);
            if (type == 'h_pic') {
                formData.append('max_width', 500);
                formData.append('max_height', -1);
            } else {
                formData.append('max_width', 500);
                formData.append('max_height', 300);
            }
            //上傳動作
            $('#pv_images_upload').remove();
            $('#remove_btn_pv_images_upload').remove();
            $('[name="images"]').val('');
            axios.post(
                '{{ url('/cms/upload/image') }}',
                formData,
                config
            ).then(function (response) {
                $('[name="images"]').val(JSON.stringify(response.data));
                let html = ' <a data-fancybox="gallery" id="pv_images_upload" href=\"' + url + response.data.url + '\" class="fc_images"><img src=\"' + url + response.data.url + '\" width=\"auto\" height=\"80px\" style="    margin-top: -4px;"></a>';
                html += '<button type="button"  id="remove_btn_pv_images_upload" to="images" class="remove_btn d-inline btn btn-block btn-outline-danger btn-xs" val="images_upload" style="width:50px;\n' +
                    '    margin-left: 15px;\n' +
                    '    margin-bottom: 3px;">移除</button>';
                $(".image_progress").after(html);

                $('.remove_btn').click(function () {
                    let id = $(this).attr('val');
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
                            $('#prize_image_progress').attr('aria-valuenow', 0);
                            $('#prize_image_progress').css('width', '0%');
                            $('#pv_' + id).remove();
                            $('#remove_btn_pv_images_upload').remove();
                            $('[name="images"]').val('');
                            console.log('delete');
                            console.log(id);

                        }
                    })
                });
            });
        }

        function IsValidJSONString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }
    </script>
@endsection
