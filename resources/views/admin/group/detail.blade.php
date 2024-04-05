@extends('layouts.admin')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Iuranku</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Group</a></li>
                        <li class="breadcrumb-item active">Group Detail</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ $data->group_name }}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="mb-0 mt-2">{{ $data->group_name }}</h4>
                    <p class="text-muted font-14">Verified Group</p>

                    <h4><span class="badge bg-success">Active</span></h4>

                    <div class="text-start mt-3">
                        <h4 class="font-13 text-uppercase">Address :</h4>
                        <p class="text-muted font-13 mb-3">
                            {{ $data->group_address }}
                        </p>

                        <h4 class="font-13 text-uppercase">Description :</h4>
                        <p class="text-muted font-13 mb-3">
                            @if ($data->description == null)
                                No Description Attatched.
                            @else
                                {{ $data->group_description }}
                            @endif
                        </p>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
            <button class="btn btn-danger" style="width: 100%">Delete Group</button>
        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#settings" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0 active">
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#members" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                Member
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="settings">
                            <form action="{{ route('admin.group.update', Crypt::encryptString($data->id)) }}"
                                method='POST'>
                                @csrf
                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Group Info </h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Group Name</label>
                                            <input type="text" class="form-control" id="name" name="group_name"
                                                value="{{ $data->group_name }}" placeholder="Name">
                                        </div>
                                    </div>
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description (Optional)</label>
                                            <textarea class="form-control" id="description" name="group_description" rows="4" placeholder="Group Description">{{ $data->group_description }}</textarea>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->



                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="group_address" rows="4" placeholder="Write something...">{{ $data->group_address }}</textarea>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success mt-2"><i class="mdi mdi-content-save"></i>
                                        Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane show" id="members">

                            <h5 class="mb-3 mt-4 text-uppercase"><i class="mdi mdi-cards-variant me-1"></i>Members</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Member Name</th>
                                            <th>Member Type</th>
                                            <th>Join Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data->group_member as $index => $item)
                                            <tr>
                                                <td>{{ $index + +1 }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->member_type->member_type_name }}</td>
                                                <td>{{ $item->join_date }}</td>
                                                <td>
                                                    @if ($item->status == 'Active')
                                                        <span class="badge badge-success-lighten">Active</span>
                                                    @else
                                                        <span class="badge badge-danger-lighten">Not Active</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="5">No Data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end tab-pane -->
                        <!-- end about me section content -->
                        <!-- end settings content-->

                    </div> <!-- end tab-content -->
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div>
    <!-- end row-->
@endsection
