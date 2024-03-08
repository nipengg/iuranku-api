@extends('layouts.admin')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Iuranku</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li>
                        <li class="breadcrumb-item active">User Detail</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ $data->name }} Profile</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="mb-0 mt-2">{{ $data->name }}</h4>
                    <p class="text-muted font-14">{{ $data->role }}</p>

                    <h4><span class="badge bg-success">Active</span></h4>

                    <div class="text-start mt-3">
                        <h4 class="font-13 text-uppercase">Address :</h4>
                        <p class="text-muted font-13 mb-3">
                            {{ $data->address }}
                        </p>
                        <p class="text-muted mb-2 font-13"><strong>Phone :</strong><span class="ms-2">{{ $data->phone }}</span></p>

                        <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span
                                class="ms-2 ">{{ $data->email }}</span></p>

                        <p class="text-muted mb-1 font-13"><strong>Gender :</strong> <span class="ms-2">{{ $data->gender }}</span></p>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#groups" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0 active">
                                Group
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#settings" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                Settings
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="groups">

                            <h5 class="mb-3 mt-4 text-uppercase"><i class="mdi mdi-cards-variant me-1"></i>Groups</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Group Name</th>
                                            <th>Member Type</th>
                                            <th>Join Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Perum 1</td>
                                            <td>Pengelola</td>
                                            <td>01/01/2024</td>
                                            <td><span class="badge badge-success-lighten">Active</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end tab-pane -->
                        <!-- end about me section content -->

                        <div class="tab-pane" id="settings">
                            <form>
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item text-danger">Delete User</a>
                                    </div>
                                </div>
                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle me-1"></i> Personal Info
                                </h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}"
                                                placeholder="Name">
                                        </div>
                                    </div>
                                </div> <!-- end row -->

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <div class="mt-1">
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="genderMale" name="gender" value="Male" class="form-check-input" @if($data->gender == 'Male') checked @endif required>
                                            <label class="form-check-label" for="genderMale">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input type="radio" id="genderFemale" name="gender" value="Female" class="form-check-input" @if($data->gender == 'Female') checked @endif required>
                                            <label class="form-check-label" for="genderFemale">Female</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="number" class="form-control" id="number" name="number" value="{{ $data->phone }}"
                                                placeholder="Phone Number">
                                        </div>
                                    </div>
                                </div> <!-- end row -->


                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="4" placeholder="Write something...">{{ $data->address }}</textarea>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="useremail" value="{{ $data->email }}"
                                                placeholder="Enter email">
                                            <span class="form-text text-muted"><small>If you want to change email please <a
                                                        href="javascript: void(0);">click</a> here.</small></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="userpassword"
                                                placeholder="Enter password">
                                            <span class="form-text text-muted"><small>If you want to change password please
                                                    <a href="javascript: void(0);">click</a> here.</small></span>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success mt-2"><i
                                            class="mdi mdi-content-save"></i> Save</button>
                                </div>
                            </form>
                        </div>
                        <!-- end settings content-->

                    </div> <!-- end tab-content -->
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
    </div>
    <!-- end row-->
@endsection
