@extends('portal.app')

@section('content')
@include('portal.modals.add_post_modal')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
<div class="main-container">
    <div class="section">
        <div class="container">
            <h1 class="title-2 center" style="margin: -40px 0 0 0;">Internet Services</h1>
            @if(Auth::user())
            @if(in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
                <div class="col-md-12">
                    <div class="pull-right">
                        <div class="form-group">
                            <button type="button" data-category="internet_services" class="btn btn-success" id="addPostBtn">
                                <i class="fa fa-plus"></i><span>Add Post</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            <div class="row">
                @foreach($internet_services as $intern)
                <div class="col-md-12">
                    @if(Auth::user())
                    @if(in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
                    <a href="#" id="editPostBtn" data-image="{{ $intern->featured_file }}" data-title="{{ $intern->title }}" data-content="{{ $intern->content }}" data-id="{{ $intern->id }}"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="#" id="deletePostBtn" data-title="{{ $intern->title }}" data-id="{{ $intern->id }}"><i class="fa fa-pencil"></i> Delete</a>
                     @endif
                     @endif
                    <div class="blog-post">
                        <div class="feature-inner">
                            <div class="embed-responsive embed-responsive-16by9">
                                <video width="320" height="240" controls poster="{{ asset('storage/videos/poster/internet_services.png') }}">
                                    <source src="{{ asset('storage/' . $intern->featured_file) }}" type="video/mp4">
                                </video>
                            </div>
                        </div>

                        <div class="post-content">
                            <h3 class="post-title">{{ $intern->title }}</h3>
                            <div class="meta">
                                <span class="meta-part"><i class="icon-user"></i> IT Department</span>
                                <span class="meta-part"><i class="icon-calendar"></i> September 20, 2017</span>
                            </div>

                            <div class="col-md-12">
                             <p>{!! $intern->content !!}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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