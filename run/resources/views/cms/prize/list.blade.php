@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/prize_list.css')) }}">
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
        .prize_type_0{
            background-color: #548173;
            color: white;
        }
        .prize_type_1{
            background-color: #999c2b;
            color: white;
        }
        .lottery_list_0{
            background-color: #d66847;
            color: white;
        }
        .lottery_list_1{
            background-color: #539c42;
            color: white;
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
                        <button type="button" class="btn bg-success btn-sm add_btn">新增獎項</button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- 具体的内容 -->
                    <table
                        data-toggle="table"
                        data-search="false">
                        <thead>
                        <tr>
                            <th data-width="50">#</th>
                            <th data-width="80">狀態</th>
                            <th data-width="100">型態</th>
                            <th data-width="100">名單來源</th>
                            <th data-width="200">獎項名稱</th>
                            <th data-width="200">數量</th>
                            <th data-width="200">抽獎</th>
                            <th data-width="200">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $no = ($result->currentPage() - 1) * $result->perPage() + 1 ;
                        @endphp
                        @foreach ($result as $row)
                            <tr id="cp_row_{{ $row->id }}" class="">
                                <td> {{ $no++ }}</td>
                                <td class="{{ $row->item_status == 0 ? 'status_off' : 'status_on' }}"> {{ $row->item_status == 0 ? '停止' : '啟用' }}</td>
                                <td class="{{ $row->prize_type == 0 ? 'prize_type_0' : 'prize_type_1' }}"> {{ $row->prize_type == 0 ? '活動單位執行' : '玩家即時抽獎' }}</td>
                                <td class="{{ $row->lottery_list == 0 ? 'lottery_list_0' : 'lottery_list_1' }}"> {{ $row->lottery_list == 0 ? '遊戲玩家' : '發票登錄' }}</td>
                                <td> {{ $row->name }}</td>
                                <td> {{ $row->qty }}</td>
                                <td class="cp_row_{{ $row->id }}_btn">
                                    @if ( $row->item_status == 1 )
                                        <button type="button"
                                                class="awards_btn_{{ $row->id }} btn bg-gradient-info btn-sm awards_btn {{ $row->has_draw == 1 ? '' : 'hide_btn' }}"
                                                data-row-uuid="{{ $row->uuid }}" data-row-id="{{ $row->id }}" data-row-lottery_list="{{ $row->lottery_list}}" >得獎名單
                                        </button>
                                        <button type="button"
                                                class="clear_btn_{{ $row->id }} btn bg-gradient-danger btn-sm clear_btn  {{ $row->has_draw == 1 ? '' : 'hide_btn' }}"
                                                data-row-uuid="{{ $row->uuid }}" data-row-id="{{ $row->id }}" data-row-lottery_list="{{ $row->lottery_list}}" >清空結果
                                        </button>
                                        <button type="button"
                                                class="draw_btn_{{ $row->id }} btn bg-gradient-warning btn-sm draw_btn  {{ $row->has_draw == 1 ? 'hide_btn' : '' }}"
                                                data-row-uuid="{{ $row->uuid }}" data-row-id="{{ $row->id }}" data-row-lottery_list="{{ $row->lottery_list}}" >執行抽獎
                                        </button>
                                    @endif
                                </td>
                                <td class="cp_row_{{ $row->id }}_btn">
                                    <button type="button" class="btn bg-gradient-info btn-sm edit_btn"
                                            data-row-uuid="{{ $row->uuid }}">修改
                                    </button>
                                    <button type="button" class="btn bg-gradient-danger btn-sm delete_btn"
                                            data-row-uuid="{{ $row->uuid }}" data-row-id="{{ $row->id }}">刪除
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
    <script src="{{ asset(mix('/js/prize_list.js')) }}"></script>
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

        $(function () {
            //新增
            $('.add_btn').click(function () {
                location.href = '{{ url('/cms/prize/create') }}';
            });
            //編輯
            $('.edit_btn').click(function () {
                let uuid = $(this).data("row-uuid");
                location.href = '{{ url('/cms/prize') }}' + '/' + uuid + '/edit';
            });

            //得獎名單
            $('.awards_btn').click(function () {
                let uuid = $(this).data("row-uuid");
                location.href = '{{ url('/cms/prize') }}' + '/' + uuid + '/awards';
            });

            //抽獎
            const  action_func_name = '';
            $('.draw_btn').click(function () {
                swal.fire({
                    title: '是否要開始抽獎?',
                    text: "",
                    type: '',
                    showCancelButton: true,
                    confirmButtonColor: '#56d665',
                    cancelButtonColor: '#aaaaaa',
                    confirmButtonText: '確定',
                    cancelButtonText: '取消',
                    focusCancel: true
                }).then((result) => {
                    if (result.value) {
                        console.log('draw it!');

                        let uuid = $(this).data("row-uuid");
                        let id = $(this).data("row-id");
                        let lottery_list = $(this).data("row-lottery_list");
                        //送出
                        $.LoadingOverlay("show", {
                            image: "../images/loading.gif",
                            imageAnimation: '',
                        });
                        const config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            },
                            onUploadProgress: function (progressEvent) {
                            }
                        }

                        let formData = new FormData();
                        formData.append('uuid', uuid);
                        axios.post(
                            '{{ url('/cms/prize') }}' + '/' + uuid + '/draw/'+lottery_list,
                            formData,
                            config
                        ).then(function (response) {
                            $.LoadingOverlay("hide");
                            if (response.data.status == 'success') {
                                // toastr.success('', response.data.message);
                                swal.fire({
                                    type: 'success',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then((result) => {
                                    $('.draw_btn_' + id).hide();
                                    $('.awards_btn_' + id).fadeIn();
                                    $('.clear_btn_' + id).fadeIn();
                                });
                            } else {
                                toastr.error('', response.data.message);
                            }
                        });
                    } else {
                        return false;
                    }
                })
            });

            //清空結果
            $('.clear_btn').click(function () {
                let uuid = $(this).data("row-uuid");
                let id = $(this).data("row-id");
                let lottery_list =$(this).data("row-lottery_list");

                swal.fire({
                    title: '確定要清空此抽獎結果資料?',
                    text: "",
                    type: '',
                    showCancelButton: true,
                    confirmButtonColor: '#d63f2d',
                    cancelButtonColor: '#aaaaaa',
                    confirmButtonText: '確定',
                    cancelButtonText: '取消',
                    focusCancel: true
                }).then((result) => {
                    if (result.value) {
                        const config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            },
                            onUploadProgress: function (progressEvent) {
                            }
                        }
                        $.LoadingOverlay("show", {
                            image: "../images/loading.gif",
                            imageAnimation: '',
                        });

                        let formData = new FormData();
                        formData.append('uuid', uuid);
                        axios.post(
                            '{{ url('/cms/prize') }}' + '/' + uuid + '/clear/'+lottery_list,
                            formData,
                            config
                        ).then(function (response) {
                            $.LoadingOverlay("hide");

                            if (response.data.status == 'success') {
                                swal.fire({
                                    type: 'success',
                                    title: response.data.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then((result) => {
                                    $('.awards_btn_' + id).hide();
                                    $('.clear_btn_' + id).hide();
                                    $('.draw_btn_' + id).fadeIn();
                                });

                            } else {
                                toastr.error('', response.data.message);
                            }
                        });
                    }
                })
            });

            //刪除
            var id = '';
            var uuid = '';
            $('.delete_btn').click(function () {
                let id = $(this).data("row-id");
                let uuid = $(this).data("row-uuid");
                let lottery_list =$(this).data("row-lottery_list");
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
                            '{{ url('/cms/prize') }}' + '/' + uuid + '/delete',
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

    </script>
@endsection
