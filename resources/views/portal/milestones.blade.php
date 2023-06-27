@extends('portal.app')

@section('content')
@include('portal.modals.add_post_modal')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
<div class="main-container">
    <div class="section">
        <div class="container">
            <h1 class="title-2 center" style="margin: -40px 0 0 0; border: 0;">Historical Milestones</h1>
            @if(Auth::user() && in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
                <div class="col-md-12">
                    <div class="pull-right">
                        <div class="form-group">
                            <button type="button" data-category="historical_milestones" class="btn btn-success" id="addPostBtn">
                                <i class="fa fa-plus"></i><span>Add Milestone</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            <div class="row">
                @foreach($milestones as $milestone)
                <div class="col-md-12">
                    <span class="section-title">{{ $milestone->title }}</span>
                    @if(Auth::user() && in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
                    <a href="#" id="editPostBtn" data-image="{{ $milestone->featured_file }}" data-title="{{ $milestone->title }}" data-content="{{ $milestone->content }}" data-id="{{ $milestone->id }}"><i class="fa fa-pencil"></i> Edit</a> |
                    <a href="#" id="deletePostBtn" data-image="{{ $milestone->featured_file }}" data-title="{{ $milestone->title }}" data-id="{{ $milestone->id }}"><i class="icon-trash"></i> Delete</a>
                     @endif
                    <div class="typography-wrap">
                        <span>{!! $milestone->content !!}</span>
                        <hr>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>

<script>
    $(document).on('click', '#editPostBtn', function(event){
        event.preventDefault();
        $('#editPostModal .post_id').val($(this).data('id'));
        $('#editPostModal .post_title').val($(this).data('title'));
        // $('#editPostModal .post_content').val($(this).data('content'));
        $('#editPostModal .original_post_image').val($(this).data('image'));
        $('#editPostModal .original_post_title').val($(this).data('title'));
        $('#editPostModal .original_post_content').val($(this).data('content'));
        CKEDITOR.instances['post_content'].setData($(this).data('content'));

        $('#editPostModal').modal('show');
    });

    $(document).on('click', '#addPostBtn', function(event){
        event.preventDefault();
        // console.log($(this).data('category'));
        $('#addPostModal .post_category').val($(this).data('category'));
        $('#addPostModal').modal('show');
    });

    $(document).on('click', '#deletePostBtn', function(event){
        event.preventDefault();
        $('#deletePostModal .post_id').val($(this).data('id'));
        $('#deletePostModal .post_title').text($(this).data('title'));
        $('#deletePostModal').modal('show');
    });

    $(document).on('click', '#deletePostBtn', function(event){
        event.preventDefault();
        $('#deletePostModal .post_id').val($(this).data('id'));
        $('#deletePostModal .post_title').text($(this).data('title'));
        $('#deletePostModal').modal('show');
    });

    // $('.ckeditor').ckeditor();

    CKEDITOR.config.height = 450;
</script>

@endsection