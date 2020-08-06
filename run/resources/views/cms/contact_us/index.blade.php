@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/contact_us_list.css')) }}">
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
        /*.btn{*/
        /*    margin-top: 2px;*/
        /*}*/


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
        .card-body img{
            max-width: 200px;
            height: auto;
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
                    <!-- 具体的内容 -->
                    <table
                        data-toggle="table"
                        data-search="false">
                        <thead>
                        <tr>
                            <th data-width="50">#</th>
                            <th data-width="100">姓名</th>
                            <th data-width="100">電話</th>
                            <th data-width="100">信箱</th>
                            <th data-width="200">內容</th>
                            <th data-width="300">回覆內容</th>
                            <th data-width="80">日期</th>
                            <th data-width="100">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $no = ($contactors->currentPage() - 1) * $contactors->perPage() + 1 ;
                        @endphp
                        @foreach ($contactors as $row)
                            <tr id="row_{{ $row->id }}" class="">
                                <td> {{ $no++ }}</td>
                                <td> {{ $row->name }}</td>
                                <td> {{ $row->phone }}</td>
                                <td> {{ $row->email }}</td>
                                <td> {{ $row->subject }} <br> {{ $row->content }} </td>
                                <td>
                                    <!-- Default box -->
                                    <div class="card collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title">展開</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                                    <i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="card-body table-responsive p-0" style="display:none;">
                                            <table class="table table-hover">
                                                <tbody>
                                                @foreach( $reply_result->whereIn('cid', $row->id) as $index => $row)
                                                    <tr>
                                                        <td> {!! htmlspecialchars_decode($row->content) !!} </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </td>
                                <td> {{ $row->created_at }}</td>
                                <td>
                                    <button type="button" class="btn bg-gradient-success btn-sm reply_btn"
                                            data-row-id="{{  $row->id }}"
                                            data-row-url="{{ route('cms.contact_us.reply',$row->id) }}">
                                        回覆
                                    </button>
                                    <button type="button" class="btn bg-gradient-danger btn-sm delete_btn"
                                            data-row-id="{{  $row->id }}"
                                            data-row-url="{{ route('cms.contact_us.destroy',$row->id) }}">
                                        刪除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <!-- pagination -->
                    <div class="float-right pagination" style="padding-top: 10px">
                        {{ $contactors->links() }}
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
    <script src="{{ asset(mix('/js/contact_us_list.js')) }}"></script>
    <script type="text/javascript">
        $(function () {
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

            //回覆
            $('.reply_btn').click(function () {
                let id = $(this).data("row-id");
                let url = $(this).data("row-url");
                location.href = url;
            });
        })

    </script>
@endsection
