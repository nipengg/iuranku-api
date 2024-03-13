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
                        <a href="{{ route('admin.user.index') }}" class="btn btn-danger ms-1">Back</a>
                    </div>
                </div>
                <h4 class="page-title">Create User</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-pane show active" id="basic-form-preview">
                        <form action="{{ route('admin.user.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                                    placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}"
                                    placeholder="Enter Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <div class="mt-1">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="genderMale" name="gender" value="Male" class="form-check-input" @if(old('gender') == 'Male') checked @endif required>
                                        <label class="form-check-label" for="genderMale">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="genderFemale" name="gender" value="Female" class="form-check-input" @if(old('gender') == 'Female') checked @endif required>
                                        <label class="form-check-label" for="genderFemale">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" id="phone" maxlength="12" value="{{ old('phone') }}"
                                    data-toggle="maxlength" placeholder="Enter Phone Number" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" cols="30" rows="3" class="form-control" placeholder="Enter Address"
                                    maxlength="250" data-toggle="maxlength">{{ old('address') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Enter Password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" id="confirm-password"
                                    placeholder="Confirm Password" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="Admin" @if(old('role') == 'Admin') selected @endif>Admin</option>
                                    <option value="User" @if(old('role') == 'User') selected @endif>User</option>
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
