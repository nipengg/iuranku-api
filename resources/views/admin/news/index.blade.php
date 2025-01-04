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
                                        <!-- Delete Modal -->
                                        <div id="delete-modal-{{ $item->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-body p-4">
                                                        <form action="{{ route('admin.news.delete', Crypt::encryptString($item->id)) }}" method="POST">
                                                            @csrf
                                                            <div class="text-center">
                                                                <i class="dripicons-warning h1 text-danger"></i>
                                                                <h4 class="mt-2">Delete Confirmation</h4>
                                                                <p class="mt-3">Are you sure want to delete this data? this action can't be undone</p>
                                                                <button type="submit" class="btn btn-danger my-2" data-bs-dismiss="modal">Delete</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->

                                        <tr class="align-middle">
                                            <td>{{ $item->news_title }}</td>
                                            <td>{{ $item->author->name }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td style="width: 100px">
                                                <a href="{{ route('admin.news.detail', Crypt::encryptString($item->id)) }}" class="btn btn-outline-primary btn-soft-primary rounded-pill">Detail</a>
                                                <a href="{{ route('admin.news.edit', Crypt::encryptString($item->id)) }}" class="btn btn-outline-info btn-soft-primary rounded-pill">Edit</a>
                                                <button class="btn btn-outline-danger btn-soft-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#delete-modal-{{ $item->id }}">Delete</button>
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
