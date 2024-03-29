@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="page-title-box">
            <div class="page-title-right">
                <div class="d-flex">
                    <a href="{{ route('admin.group.create') }}" class="btn btn-primary ms-1">Create Group</a>
                </div>
            </div>
            <h4 class="page-title">Group</h4>
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
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr class="align-middle">
                                            <td>{{ $item->group_name }}</td>
                                            <td>{{ $item->group_address }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.group.detail', Crypt::encryptString($item->id)) }}" class="btn btn-outline-primary btn-soft-primary rounded-pill">Detail</a>
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
