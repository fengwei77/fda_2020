@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/about_list.css')) }}">
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

        button {
            margin: 1px;
        }

        .hide_btn {
            display: none;
        }

        .status_on {
            margin: 2px;
        }

        .status_off {
            background-color: #919191;
            color: white;
        }

        .add_btn {
            padding: 5px 20px;
        }

        .list_catch, .list_sharp {
            text-align: center;
        }

        .list_catch i {
            display: none;
        }

        .list_catch:hover i {
            display: inline-block;
        }

        .blue-background-class {
            background-color: #C8EBFB;
        }

        .thumbnail {
            height: 50px;
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
                <div class="card-header">
                    <h3 class="card-title">&nbsp;</h3>
                    <div class="card-tools">
                        <button type="button" class="btn bg-success btn-sm add_btn">新增</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- 具体的内容 -->
                    <table
                        data-toggle="table"
                        data-search="false">
                        <thead>
                        <tr>
                            <th data-width="50" class="list_sharp">#</th>
                            <th data-width="80">狀態</th>
                            <th data-width="100">位置</th>
                            <th data-width="300">圖片</th>
                            <th data-width="200">操作</th>
                        </tr>
                        </thead>
                        <tbody
                            id="list"
                            class="faa-parent  animated-hover">
                        @php
                            $no = ($result->currentPage() - 1) * $result->perPage() + 1 ;
                        @endphp
                        @foreach ($result as $row)
                            <tr id="cp_row_{{ $row->id }}" data-id="{{$row->id}}">
                                <td class="list_catch"><i
                                        class="fas fa-arrows-alt-v faa-slow faa-vertical animated"></i> <span
                                        class="list_seq">{{ $no++ }}</span></td>
                                <td class="{{ $row->item_status == 0 ? 'status_off' : 'status_on' }}"> {{ $row->item_status == 0 ? '停止' : '啟用' }}</td>
                                <td>

                                </td>
                                <td>
                                    @if ($row->images != '')
                                        <a data-fancybox href="{{ url( json_decode($row->images)->url)  }}">
                                            <img src="{{ url( json_decode($row->images)->url)  }}" class="thumbnail">
                                        </a>
                                    @endif
                                </td>
                                <td class="cp_row_{{ $row->id }}_btn">
                                    <button type="button" class="btn bg-gradient-info btn-sm edit_btn"
                                            data-row-uuid="{{ $row->id }}">修改
                                    </button>
                                    <button type="button" class="btn bg-gradient-danger btn-sm delete_btn"
                                            data-row-uuid="{{ $row->id }}" data-row-id="{{ $row->id }}">刪除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="float-right pagination" style="padding-top: 10px">
                        {{ $result->links() }}
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset(mix('/js/about_list.js')) }}"></script>
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
            if ($("#list tr").length > 1) {
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
                location.href = '{{ url('/cms/about/create') }}';
            });
            //編輯
            $('.edit_btn').click(function () {
                let uuid = $(this).data("row-uuid");
                location.href = '{{ url('/cms/about') }}' + '/' + uuid + '/edit';
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
                            '{{ url('/cms/about') }}' + '/destroy/' + id,
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

        function seq_refresh(id, position, old_position) {
            let result = 'success';
            const config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function (progressEvent) {
                }
            }
            $.LoadingOverlay("show", {
                image: "",
                progress: true
            });

            let formData = new FormData();
            formData.append('id', id);
            formData.append('position', position);
            formData.append('old_position', old_position);
            axios.post(
                '{{ url('/cms/about/sorting') }}',
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
