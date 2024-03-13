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
                        <a href="{{ route('admin.news.index') }}" class="btn btn-danger ms-1">Back</a>
                    </div>
                </div>
                <h4 class="page-title">Create News</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-pane show active" id="basic-form-preview">
                        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="news_title" class="form-label">News Title</label>
                                <input type="text" name="news_title" class="form-control" id="news_title"
                                    value="{{ old('news_title') }}" placeholder="Enter News Title" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea id="content" name="content">{{ old('content') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div> <!-- end preview-->
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>

    <script src="//cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('content');
    </script>
@endsection
