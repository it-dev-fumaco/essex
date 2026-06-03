@extends('admin.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="inner-box featured">
            <h2 class="title-2">Add Document</h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the errors below.</strong>
                </div>
            @endif

            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Description (optional)</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>File</label>
                    <input type="file" name="file" class="form-control" required>
                    @error('file')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload"></i> Upload
                    </button>
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-default">Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

