@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                            <form action="/admin/book_recommend/index" method="get" class="form-inline" enctype="multipart/form-data">
                                <div class="form-group mx-1">
                                    <label for="exampleInputEmail1" class="mx-1">{{trans('default.book_control.book_type') ?? '小說類型'}}</label>
                                    <select class="form-control form-control-lg" name="book_type_id" id="book_type_id">
                                        <option value="">all</option>
                                        @foreach($book_types as $book_type)
                                            <option value="{{$book_type->book_type_id}}" {{($bookTypeId ?? '') == $book_type->book_type_id ? 'selected' : ''}}>
                                                {{$book_type->type_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mx-1">
                                    <label for="exampleInputEmail1" class="mx-1">{{trans('default.book_control.book_name') ?? '名稱'}}</label>
                                    <input type="text" class="form-control" name="book_name" placeholder="{{trans('default.book_control.book_name') ?? '名稱'}}" value="{{$bookName ?? ''}}">
                                </div>
                                <button type="submit" class="btn btn-primary mx-1">{{trans('default.submit') ?? '送出'}}</button>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                                       aria-describedby="example2_info">
                                    <thead>
                                    <tr>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2"
                                            rowspan="1"
                                            colspan="1" aria-sort="ascending"
                                            aria-label="Rendering engine: activate to sort column descending">{{trans('default.id') ?? '序號'}}
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                            colspan="1"
                                            aria-label="Browser: activate to sort column ascending">{{trans('default.book_control.book_type') ?? '類型'}}
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                            colspan="1"
                                            aria-label="Browser: activate to sort column ascending">{{trans('default.book_control.book_name') ?? '名稱'}}
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                            colspan="1"
                                            aria-label="CSS grade: activate to sort column ascending">{{trans('default.book_control.book_status') ?? '狀態'}}
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                            colspan="1"
                                            aria-label="CSS grade: activate to sort column ascending">{{trans('default.book_control.book_img') ?? '封面'}}
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                            colspan="1"
                                            aria-label="CSS grade: activate to sort column ascending">{{trans('default.book_control.book_recommend_create_time') ?? '推薦時間'}}
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                            colspan="1"
                                            aria-label="CSS grade: activate to sort column ascending">{{trans('default.action') ?? '動作'}}
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($datas as $book)
                                        <tr class="odd">
                                            <td class="sorting_1 dtr-control">{{ $book->book_id }}</td>
                                            <td>{{ $book->type_name }}</td>
                                            <td>{{ $book->book_name }}</td>
                                            <td>{{ $book->status }}</td>
                                            <td>
                                                <img src="{{$book->cover_img}}" alt="image" style="width:100px">
                                            </td>
                                            <td>{{ $book->create_time }}</td>
                                            <td>
                                                @if(authPermission('bookrecommend-delete'))
                                                    <form action="/admin/book_recommend/delete" method="post" _method="delete">
                                                        <input type="hidden" name="_method" value="delete">
                                                        <input type="hidden" name="id" value="{{$book->book_id}}">
                                                        <input type="hidden" name="book_type_id" value="{{$bookTypeId}}" >
                                                        <input type="hidden" name="book_name" value="{{$bookName}}" >
                                                        <input type="hidden" name="page" value="{{$page}}" >
                                                        <input type="submit" class="btn btn-danger"
                                                               value="{{trans('default.delete') ?? '刪除'}}">
                                                    </form>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">{{trans('default.id') ?? '序號'}}</th>
                                        <th rowspan="1" colspan="1">{{trans('default.book_control.book_type') ?? '類型'}}</th>
                                        <th rowspan="1" colspan="1">{{trans('default.book_control.book_name') ?? '名稱'}}</th>
                                        <th rowspan="1" colspan="1">{{trans('default.book_control.book_status') ?? '狀態'}}</th>
                                        <th rowspan="1" colspan="1">{{trans('default.book_control.book_img') ?? '封面'}}</th>
                                        <th rowspan="1" colspan="1">{{trans('default.book_control.book_recommend_create_time') ?? '推薦時間'}}</th>
                                        <th rowspan="1" colspan="1">{{trans('default.action') ?? '動作'}}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                                    {{trans('default.table_page_info',[
                                            'page' => $page,
                                            'total' => $total,
                                            'last_page' => $last_page,
                                            'step' => $step,
                                        ]) ?? '顯示第 $page 頁
                                        共 $total 筆
                                        共 $last_page 頁
                                        每頁顯示 $step 筆'}}
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button page-item previous {{$page <= 1 ? 'disabled' : ''}}"
                                            id="example2_previous">
                                            <a href="{{$prev}}"
                                               aria-controls="example2" data-dt-idx="0" tabindex="0"
                                               class="page-link">{{trans('default.pre_page') ?? '上一頁'}}</a>
                                        </li>
                                        <li class="paginate_button page-item next {{$last_page <= $page ? 'disabled' : ''}}"
                                            id="example2_next">
                                            <a href="{{$next}}"
                                               aria-controls="example2"
                                               data-dt-idx="7"
                                               tabindex="0"
                                               class="page-link">{{trans('default.next_page') ?? '下一頁'}}</a>
                                        </li>
                                    </ul>


                                </div>
                            </div>
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