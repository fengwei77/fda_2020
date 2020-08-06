@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/newsletter_list.css')) }}">
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
                    <!-- 具体的内容 -->
                    <form role="search" method="GET">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="text" class="form-control col-lg-3 col-md-4 col-xs-12 col-sm-12 btn-group search_q"
                               name="q" id="q" placeholder="Search">
                        <button type="submit" class="btn btn-default  btn-group"> 搜尋</button>
                        <button type="button" class="btn btn-default  btn-group"
                                onclick="location.href='{{ url()->current() }}';"> 重置
                        </button>
                        <button type="button" class="btn bg-success btn-sm add_btn btn-group float-right">新增</button>
                        <button type="button" class="btn bg-warning btn-sm add_tester_btn btn-group float-right"
                                style="margin-right: 5px;">測試信箱設定
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <table
                        data-toggle="table"
                        data-search="false">
                        <thead>
                        <tr>
                            <th data-width="50">#</th>
                            <th data-width="100">標題</th>
                            <th data-width="200">主旨</th>
                            {{--                            <th data-width="400">內容</th>--}}
                            <th data-width="200">日期</th>
                            <th data-width="150">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($newsletters as $row)
                            <tr id="row_{{ $row->id }}" class="">
                                <td> {{ $row_number++ }}</td>
                                <td> {{ $row->title }}</td>
                                <td> {{ $row->subject }} [ <a data-fancybox data-src="#modal_{{ $row->id }}"
                                                              href="javascript:;">信件內容</a> ]
                                    <div style="display: none;" id="modal_{{ $row->id }}">
                                        主旨:{{ $row->title }}
                                        <br>
                                        {!! htmlspecialchars_decode($row->content) !!}
                                    </div>
                                </td>
                                {{--                                <td> {!! htmlspecialchars_decode($row->content) !!} </td>--}}

                                <td> {{ $row->created_at }}</td>
                                <td>
                                    <button type="button" class="btn bg-gradient-gray btn-sm send_test_btn"
                                            data-row-id="{{ $row->id }}"
                                            data-row-url="{{ route('cms.newsboy.test',$row->id) }}">
                                        測試
                                    </button>
                                    <button type="button" class="btn bg-gradient-blue btn-sm send_btn"
                                            data-row-id="{{ $row->id }}"
                                            data-row-url="{{ route('cms.newsboy.index',$row->id) }}">
                                        正式送信
                                    </button>
                                    <button type="button" class="btn bg-gradient-info btn-sm edit_btn"
                                            data-row-id="{{ $row->id }}"
                                            data-row-url="{{ route('cms.newsletters.edit',$row->id) }}">
                                        編輯
                                    </button>
                                    <button type="button" class="btn bg-gradient-danger btn-sm delete_btn"
                                            data-row-id="{{  $row->id }}"
                                            data-row-url="{{ route('cms.newsletters.destroy',$row->id) }}">
                                        刪除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="float-right pagination" style="padding-top: 10px">
                        {{ $newsletters->appends(Request::all())->links() }}
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
    <script src="{{ asset(mix('/js/newsletter_list.js')) }}"></script>
    <script type="text/javascript">
        $(function () {
            //發測試信
            $('.send_test_btn').click(function () {
                let id = $(this).data("row-id");
                let url = $(this).data("row-url");
                swal.fire({
                    title: '確定要開始發送測試信件?',
                    text: "",
                    showCancelButton: true,
                    confirmButtonColor: '#3977dd',
                    cancelButtonColor: '#747474',
                    confirmButtonText: '是的!',
                    cancelButtonText: '取消',
                }).then(function (result) {
                    $.LoadingOverlay("show", {
                        background: 'rgba(20, 20, 20, 0.8)',
                        image: "{!! url('images/loading.gif') !!}",
                        imageAnimation: ""
                    });

                    if (result.value) {
                        const config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                        axios.get(
                            url,
                            config
                        ).then(function (response) {
                            if (response.data.result == '01') {
                                toastr.success('', '測試信件寄送中...');
                            } else {
                                toastr.error('', '測試信件寄送失敗...');
                            }
                            $.LoadingOverlay("hide");
                        });
                    } else {
                        $.LoadingOverlay("hide");
                    }
                })
            });
            //發信
            $('.send_btn').click(function () {
                let id = $(this).data("row-id");
                let url = $(this).data("row-url");
                swal.fire({
                    title: '確定要開始發送信件?',
                    text: "",
                    showCancelButton: true,
                    confirmButtonColor: '#3977dd',
                    cancelButtonColor: '#747474',
                    confirmButtonText: '是的!',
                    cancelButtonText: '取消',
                }).then(function (result) {
                    $.LoadingOverlay("show", {
                        background: 'rgba(20, 20, 20, 0.8)',
                        image: "{!! url('images/loading.gif') !!}",
                        imageAnimation: ""
                    });
                    if (result.value) {
                        const config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                        axios.get(
                            url,
                            config
                        ).then(function (response) {
                            if (response.data.result == '01') {
                                toastr.success('', '信件寄送中...');
                            } else {
                                toastr.error('', '信件寄送失敗...');
                            }
                            $.LoadingOverlay("hide");
                        });
                    } else {
                        $.LoadingOverlay("hide");
                    }
                })
            });

            //新增
            $('.add_btn').click(function () {
                location.href = "{{ route('cms.newsletters.create') }}";
            });
            //測試信箱
            $('.add_tester_btn').click(function () {
                location.href = "{{ route('cms.newsletter_testers.index') }}";
            });
            //編輯
            $('.edit_btn').click(function () {
                let url = $(this).data("row-url");
                location.href = url;
            });

            //刪除
            var id = '';
            var uuid = '';
            $('.delete_btn').click(function () {
                let id = $(this).data("row-id");
                let url = $(this).data("row-url");
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
                        const config = {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                        axios.delete(
                            url,
                            config
                        ).then(function (response) {
                            if (response.data.result == '01') {
                                $('#row_' + id).addClass('strikeout');
                                $('#row_' + id).find('.edit_btn').remove();
                                $('#row_' + id).find('.delete_btn').remove();
                                $('.row_' + id + "_btn").html('');
                            }
                        });

                    }
                })
            });
        })

    </script>
@endsection
