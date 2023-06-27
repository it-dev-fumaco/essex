@extends('portal.app')

@section('content')
@include('portal.modals.add_post_modal')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
<div class="main-container">
    <div class="section">
        <div class="container">
            <h1 class="title-2 center" style="margin: -40px 0 0 0; border: 0;">Updates</h1>
             @if(Auth::user())
             @if(in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))

                <div class="col-md-12">
                    <div class="pull-right">
                        <div class="form-group">
                            <button type="button" data-category="updates" class="btn btn-success" id="addPostBtn">
                                <i class="fa fa-plus"></i><span>Add Post</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            <div class="row">
                @foreach($updates as $update)
                <div class="col-md-12">
                    <span class="section-title">{{ $update->title }}</span>
                     @if(Auth::user())
                     @if(in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
                    <a href="#" id="editPostBtn" data-image="{{ $update->featured_file }}" data-title="{{ $update->title }}" data-content="{{ $update->content }}" data-id="{{ $update->id }}"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="#" id="deletePostBtn" data-title="{{ $update->title }}" data-id="{{ $update->id }}"><i class="fa fa-pencil"></i> Delete</a>
                     @endif
                     @endif
                    <div class="typography-wrap">
                        <p>{!! $update->content !!}</p>
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

    CKEDITOR.config.height = 450;
</script>
@endsection