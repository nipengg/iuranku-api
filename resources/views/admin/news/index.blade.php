@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="page-title-box">
            <div class="page-title-right">
                <div class="d-flex">
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary ms-1">Create News</a>
                </div>
            </div>
            <h4 class="page-title">News</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane show active" id="buttons-table-preview">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>News Title</th>
                                        <th>Author</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr class="align-middle">
                                            <td>{{ $item->news_title }}</td>
                                            <td>{{ $item->author->name }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.news.detail', Crypt::encryptString($item->id)) }}" class="btn btn-outline-primary btn-soft-primary rounded-pill">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
@endsection
