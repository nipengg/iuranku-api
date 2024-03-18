@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            @if ($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error_message'))
                <div class="alert alert-danger mt-2">
                    {{ session('error_message') }}
                </div>
            @endif

            <div class="page-title-box">
                <div class="page-title-right">
                    <div class="d-flex">
                        <a href="{{ route('admin.group.index') }}" class="btn btn-danger ms-1">Back</a>
                    </div>
                </div>
                <h4 class="page-title">Create Group</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-pane show active" id="basic-form-preview">
                        <form action="{{ route('admin.group.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="group_name" class="form-control" id="name"
                                    value="{{ old('name') }}" placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description(Optional)</label>
                                <textarea name="group_description" id="description" cols="30" rows="3" class="form-control"
                                    placeholder="Enter Description" maxlength="250" data-toggle="maxlength">{{ old('description') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="group_address" id="address" cols="30" rows="3" class="form-control"
                                    placeholder="Enter Address" maxlength="250" data-toggle="maxlength">{{ old('address') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="users[]" class="form-label">Group Leader</label>
                                <select name="users[]" id="users[]" class="select2 form-control select2-multiple" data-toggle="select2"
                                    multiple="multiple" data-placeholder="Choose ..." >
                                    @foreach ($users as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div> <!-- end preview-->
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
@endsection
