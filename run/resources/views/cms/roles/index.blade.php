@extends('cms.layouts.master')
@section('title' , $page_title)
@section('style')
    <link rel="stylesheet" href="{{ asset(mix('/css/role_list.css')) }}">
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
                <div class="card-header">
                    <h3 class="card-title">&nbsp;</h3>
                    <div class="card-tools">
                        <a href="{{ URL::to('cms/roles/create') }}" class="btn btn-success  btn-sm add_btn">新增角色</a>

                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>角色</th>
                            <th>權限</th>
                            <th>操作</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($roles as $role)
                            <tr id="row_{{ $role->id }}" class="">
                                <td>{{ $role->name }}</td>
                                <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                <td>
                                    <a href="{{ URL::to('cms/roles/'.$role->id.'/edit') }}" class="btn bg-gradient-info btn-sm edit_btn" style="margin-right: 3px;">編輯</a>
                                    <button type="button" class="btn bg-gradient-danger btn-sm delete_btn"
                                            data-row-id="{{  $role->id }}"
                                            data-row-url="{{ route('cms.roles.destroy',$role->id) }}">
                                        刪除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
@endsection

@section('scripts')
    <script src="{{ asset(mix('/js/role_list.js')) }}"></script>
    <script type="text/javascript">
        $(function () {
            //刪除
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
