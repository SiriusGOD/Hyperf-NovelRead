@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <form action="/admin/book/store" method="post" class="col-md-12" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="{{$model->book_id ?? null}}">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{trans('default.id') ?? '序號'}}</label>
                                    <p>{{$model->book_id ?? 0}}</p>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{trans('default.book_control.book_type') ?? '類型'}}</label>
                                    <p>{{$model->type_name ?? ''}}</p>
                                </div>
                                <div class="form-group">
                                    <label for="book_name">{{trans('default.book_control.book_name') ?? '名稱'}}</label>
                                    <input type="text" class="form-control" name="book_name" id="book_name" placeholder="{{trans('default.book_control.book_name_def') ?? '請輸入名稱'}}" value="{{$model->book_name ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="book_auther">{{trans('default.book_control.book_auther') ?? '作者'}}</label>
                                    <input type="text" class="form-control" name="book_auther" id="book_auther" placeholder="{{trans('default.book_control.book_auther_def') ?? '請輸入作者'}}" value="{{$model->author ?? ''}}">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">{{trans('default.submit') ?? '送出'}}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->


        </div>
        <!-- /.col -->
    </div>

@endsection