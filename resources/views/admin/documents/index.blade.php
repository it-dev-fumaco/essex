@extends('admin.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="inner-box featured">
            <h2 class="title-2">Documents</h2>

            <div>
                <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Document
                </a>
                <br><br>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <center>{{ session('success') }}</center>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Uploaded By</th>
                            <th>Uploaded At</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $document)
                            @php
                                $media = $document->getFirstMedia('file');
                                $type = $media ? strtoupper($media->extension ?: '') : '';
                                $uploader = optional($document->uploadedByAdmin)->name ?: '-';
                            @endphp
                            <tr>
                                <td>{{ $document->title }}</td>
                                <td>{{ $type }}</td>
                                <td>{{ $uploader }}</td>
                                <td>{{ optional($document->created_at)->format('Y-m-d h:i A') }}</td>
                                <td>
                                    <a class="btn btn-xs btn-success" href="{{ route('admin.documents.download', $document) }}">
                                        <i class="fa fa-download"></i> Download
                                    </a>

                                    <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this document?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <center>No documents yet.</center>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="text-center">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

