@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="page-title-box">
            <h4 class="page-title">News</h4>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="mt-3">
                    <h2>{{ $data->news_title }}</h2>

                    <hr>

                    <div class="d-flex mb-3 mt-1">
                        <div class="w-100 overflow-hidden">
                            <small class="float-end">{{ $data->created_at }}</small>
                            <h6 class="m-0 font-14">{{ $data->author->name }}</h6>
                            <small class="text-muted">Author: {{ $data->author->email }}</small>
                        </div>
                    </div>
                    
                    {!! $data->content !!}

                </div>
                <!-- end .mt-4 -->
            </div>
            <div class="clearfix"></div>
        </div> <!-- end card-box -->
    </div>
@endsection
