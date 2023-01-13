@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <form action="/admin/book_count/index" method="get" class="form-inline" enctype="multipart/form-data">
                            <div class="form-group mx-1">
                                <label for="exampleInputEmail1" class="mx-1">{{trans('default.start_time') ?? '開始時間'}}</label>
                                <input type="text" class="form-control" name="start_time" placeholder="name" value="{{$start_time ?? \Carbon\Carbon::yesterday()}}">
                            </div>
                            <div class="form-group mx-1">
                                <label for="exampleInputEmail1" class="mx-1">{{trans('default.end_time') ?? '結束時間'}}</label>
                                <input type="text" class="form-control" name="end_time" id="name" placeholder="name" value="{{$end_time ?? \Carbon\Carbon::now()}}">
                            </div>
                            <div class="form-group mx-1">
                                <label for="exampleInputEmail1" class="mx-1">{{trans('default.book_type') ?? '小說類型'}}</label>
                                <select class="form-control form-control-lg" name="type_id">
                                    <option value="">all</option>
                                    @foreach($booktypes as $booktype)
                                        <option value="{{$booktype->book_type_id}}" {{($type_id ?? '') == $booktype->book_type_id ? 'selected' : ''}}>
                                                {{$booktype->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mx-1">{{trans('default.submit') ?? '送出'}}</button>
                        </form>
                    </div>
                    <div class="row">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->


        </div>
        <!-- /.col -->
    </div>

    <script>
        const labels = @json($labels);

        const randomNum = () => Math.floor(Math.random() * (235 - 52 + 1) + 52);

        const randomRGB = () => `rgb(${randomNum()}, ${randomNum()}, ${randomNum()})`;

        const data = {
            labels: labels,
            datasets: [
                @foreach($models as $key => $value)
                {
                    label: '{{$type_name[$key]}}',
                    fill: false,
                    tension: 0,
                    borderColor: randomRGB(),
                    data: @json($value),
                },
                @endforeach
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {}
        };

        $(function() {
            $('input[name="start_time"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-M-DD'
                }
            });
            $('input[name="end_time"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-M-DD'
                }
            });

            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        });
    </script>
@endsection