@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/seo_module.css')) }}">
    <style>
        table {
            border-collapse: collapse;
            empty-cells: show;
        }

        td {
            position: relative;
        }

        tr.strikeout td:before {
            content: " ";
            position: absolute;
            top: 50%;
            left: 0;
            border-bottom: 2px solid #2d2d2d;
            width: 100%;
        }

        .add_btn {
            padding: 5px 20px;
        }

        .search_q {
            margin-bottom: 2px;
        }

        .btn {
            margin: 1px;
        }

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

        .tagify__tag > div > * {
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
                    {{ Form::open(array('url' => 'cms/seo_module/store')) }}
                    {{ Form::Hidden('lang', '0') }}
                    <div class="input-group">
                        <div class="form-group   col-6">
                            {{ Form::label('property_name', '屬性名稱', array('class' => 'h5')) }}
                            {{ Form::text('property_name', '' , array('class' => 'form-control', 'placeholder'=> '輸入屬性名稱: property, itemprop, name....')) }}
                        </div>
                        <div class="form-group   col-6">
                            {{ Form::label('property_value', '屬性名稱', array('class' => 'h5')) }}
                            {{ Form::text('property_value', '' , array('class' => 'form-control', 'placeholder'=> '輸入屬性內容: description, og:title, article:tag ...')) }}
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="form-group  col-12">
                            {{ Form::label('content', '資料內容', array('class' => 'h5')) }}
                            {{ Form::text('content', '' , array('class' => 'form-control', 'placeholder'=> '輸入資料內容: This is the page description')) }}
                        </div>
                    </div>

                    {{ Form::submit('新增', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}

                </div>
                <!-- /.card-body -->
                <div class="card-body">
                    <table
                        data-toggle="table"
                        data-search="false">
                        <thead>
                        <tr>
                            <th data-width="50">#</th>
                            <th data-width="200">標籤名稱</th>
                            <th data-width="200">標籤屬性</th>
                            <th data-width="200">內容</th>
                            <th data-width="80">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($seo_modules as $row)
                            <tr id="row_{{ $row->id }}" class="">
                                <td> {{ $row_number++ }}</td>
                                <td> {{ $row->property_name }}</td>
                                <td> {{ $row->property_value }}</td>
                                <td> {{ $row->content }}</td>
                                <td>
                                    <button type="button" class="btn bg-gradient-danger btn-sm delete_btn"
                                            data-row-id="{{  $row->id }}"
                                            data-row-url="{{ route('cms.seo_module.destroy',$row->id) }}">
                                        刪除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="float-right pagination" style="padding-top: 10px">
                        {{ $seo_modules->appends(Request::all())->links() }}
                    </div>
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
    <script src="{{ asset(mix('/js/seo_module.js')) }}"></script>
    <script>

        $(function () {
            //送出
            $('.seo_module_form').submit(function () {
                $.LoadingOverlay("show", {
                    image: "",
                    fontawesome: "fas fa-circle-notch fa-spin"
                });
            });
        });

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

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-center toast-bottom-full-width",
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
        };

        @if ($message = Session::get('success'))
        toastr.success('', '{{ $message }}');
        @endif
        @if ($message = Session::get('failed'))
        toastr.error('', '{{ $message }}');
            @endif

        var prevPagesOrder = [];
        $(function () {
            //sort drag
            if($("#list tr").length>1) {
                var first_id = $("#list tr").filter("[data-index='0']")[0].id;
                var init_seq = parseInt($('#' + first_id + ' .list_seq').text());
                var el = document.getElementById('list');
                var list = Sortable.create(el,
                    {
                        handle: '.list_catch',
                        animation: 150,
                        ghostClass: 'blue-background-class',
                        onEnd: function (evt) {
                            let tr_id = evt.item.id;
                            let data_id = $('#' + tr_id).attr('data-id');
                            let data_position = evt.newIndex;
                            let data_old_position = evt.oldIndex;
                            // console.log(tr_id);
                            // console.log(data_id);
                            // console.log(evt.newIndex);
                            //更新至DB
                            console.log(seq_refresh(data_id, data_position + 1, data_old_position + 1));
                            let $trs = $(this).children('tr');
                            $trs.each(function () {
                                let newVal = init_seq + 1;
                                $(this).children('.list_seq').html(newVal);
                            });
                            let new_seq = init_seq;
                            $("#list").find("tr").each(function () {
                                $(this).children().eq(0).find('.list_seq').text(new_seq);
                                new_seq++;
                            });

                        }
                    });
            }
            //新增
            $('.add_btn').click(function () {
                location.href = '{{ url('/cms/product/create') }}';
            });
            //編輯
            $('.edit_btn').click(function () {
                let uuid = $(this).data("row-uuid");
                location.href = '{{ url('/cms/product') }}' + '/' + uuid + '/edit';
            });


            //刪除
            var id = '';
            var uuid = '';
            $('.delete_btn').click(function () {
                let id = $(this).data("row-id");
                let uuid = $(this).data("row-uuid");
                let lottery_list = $(this).data("row-lottery_list");
                swal.fire({
                    title: '確定是否要刪除?',
                    text: "",
                    type: '',
                    showCancelButton: true,
                    confirmButtonColor: '#d63f2d',
                    cancelButtonColor: '#aaaaaa',
                    confirmButtonText: '是的',
                    cancelButtonText: '取消',
                    focusCancel: true
                }).then(function (result) {
                    if (result.value) {
                        const config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                        axios.delete(
                            '{{ url('/cms/product') }}' + '/' + uuid + '/delete',
                            config
                        ).then(function (response) {
                            if (response.data.result == '01') {
                                $('#cp_row_' + id).addClass('strikeout');
                                $('.cp_row_' + id + "_btn").html('');
                            }
                        });

                    }
                })
            });

        });

        function seq_refresh(id , position , old_position){
            let result = 'success';
            const config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function (progressEvent) {
                }
            }
            $.LoadingOverlay("show", {
                image       : "",
                progress    : true
            });

            let formData = new FormData();
            formData.append('id', id);
            formData.append('position', position);
            formData.append('old_position', old_position);
            axios.post(
                '{{ url('/cms/product/sorting') }}',
                formData,
                config
            ).then(function (response) {
                console.log(response);
                $.LoadingOverlay("hide");
                if (response.data.message == 'success') {
                    result = response.data.message;
                } else {
                    result = 'failed';
                    toastr.error('', response.data.message);
                }
            });

            return result;
        }


    </script>
@endsection
