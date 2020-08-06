@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/user_list.css')) }}">
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
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        {{--                        <h1>{{$prize_data->name}} : {{$page_title}}</h1>--}}
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
                        <button type="button" class="btn bg-gradient-gray btn-sm bak_btn">返回上一頁</button>
                    </div>
                </div>
                <div class="card-body">
                    @role('isAdmin')
                    {{--                    <div class="float-left" style="padding: 10px">--}}
                    {{--                        <button type="button" class="btn bg-gradient-success btn-sm export_btn" uuid="{{$uuid}}">--}}
                    {{--                            <i class="far fa-file-excel"></i>--}}
                    {{--                            檔案下載</button>--}}
                    {{--                    </div>--}}
                    @endrole
                    <!-- pagination -->
                    <div class="float-right pagination" style="padding-top: 10px">
                        {{ $result->links() }}
                    </div>
                    <!-- 具体的内容 -->
                    <table
                        data-toggle="table"
                        data-search="false">
                        <thead>
                        <tr>
                            <th colspan="6">
                                <h1>{{$prize_data->name}}</h1>
                            </th>
                        </tr>
                        <tr>
                            <th data-width="50">#</th>
                            @if ($prize_data->lottery_list)
                                <th data-width="200">發票號碼</th>
                                <th data-width="200">發票日期</th>
                            @endif
                            <th data-width="200">姓名</th>
                            <th data-width="200">電話</th>
                            <th data-width="200">信箱</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $no = ($result->currentPage() - 1) * $result->perPage() + 1 ;
                        @endphp
                        @foreach ($result as $row)
                            <tr id="cp_row_{{ $row->id }}" class="">
                                <td> {{ $no++ }}</td>
                                @if ($prize_data->lottery_list)
                                    <td> {{ $row->invoice }}</td>
                                    <td> {{ date('Y/m/d', strtotime($row->invoice_date)) }}</td>
                                @endif
                                <td> {{ $row->username }}</td>
                                <td> {{ $row->phone }}</td>
                                <td> {{ $row->email }}</td>
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

        $(function () {
            //新增
            $('.bak_btn').click(function () {
                window.history.back();
                window.location.replace("{{ url('/cms/prize') }}");

            });

            $('.export_btn').click(function () {
                axios({
                    url: '{!! url( 'cms/prize/' . $uuid . '/export_awards' ) !!}',
                    method: 'GET'
                }).then((response) => {
                    if (response.data.length > 10) {
                        let link = document.createElement('a');
                        link.href = '{!! url('storage') !!}/' + response.data;
                        link.download = "";
                        link.click();
                    }
                });
            });

        })

    </script>
@endsection
