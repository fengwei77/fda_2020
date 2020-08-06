@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/player_list.css')) }}">
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
{{--                            <th data-width="200">ID</th>--}}
                            <th data-width="200">名稱</th>
                            <th data-width="200">email</th>
                            <th data-width="200">電話</th>
                            <th data-width="200">日期</th>
{{--                            <th data-width="200">操作</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $no = ($result->currentPage() - 1) * $result->perPage() + 1 ;
                        @endphp
                        @foreach ($result as $row)
                            <tr id="cp_row_{{ $row->id }}" class="">
                                <td> {{ $no++ }}</td>
{{--                                <td> {{ $row->fb_id }}</td>--}}
                                <td> {{ $row->username }}</td>
                                <td> {{ $row->email }}</td>
                                <td> {{ $row->phone }}</td>
                                <td> {{ $row->created_at }}</td>
{{--                                <td class="cp_row_{{ $row->id }}_btn">--}}
{{--                                    <button type="button" class="btn bg-gradient-danger btn-sm delete_btn"--}}
{{--                                            data-row-uuid="{{ $row->uuid }}" data-row-id="{{ $row->id }}">刪除--}}
{{--                                    </button>--}}
{{--                                </td>--}}
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
    <script src="{{ asset(mix('/js/player_list.js')) }}"></script>
    <script type="text/javascript">
        $(function () {
           //刪除
            var id = '';
            var uuid = '';
            $('.delete_btn').click(function () {
                id = $(this).data("row-id");
                uuid = $(this).data("row-uuid");
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
                            '{{ url('/cms/player') }}' + '/' + uuid + '/delete',
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
        })

    </script>
@endsection
