@extends('portal.app')

@section('content')
@include('portal.modals.add_post_modal')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
<div class="main-container">
    <div class="section">
        <div class="container-fluid">
            <div class="col-md-10 col-md-offset-1">
                <div class="row" style="margin-top: -40px; margin-left: 20px;">
                    <div class="col-md-6">
                        <h1 class="title-2" style="border: 0;">User Manuals</h1>
                    </div>
                    <div class="col-md-4 col-md-offset-2">
                        <input type="text" id="search-string" class="form-control" placeholder="Search Manuals..." style="font-size: 9pt !important; height: 20px;" value='{{ request("search") ? request("search") : null }}'>
                    </div>
                </div>
    
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissable">
                        <span>{{ session()->get('error') }}</span>
                    </div>
                @endif
                
                @if ($tag)
                    <h3>Tag: {{ $tag }}</h3>
                    <br>
                @endif
                <div id="manuals-tbl" class="row"></div>
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

    load_tbl();
    function load_tbl(){
        // var search_string = '{{ request("search") ? request("search") : null }}';
        // search_string = $('#search-string').val() ? $('#search-string').val() : search_string;
        $.ajax({
            type:'GET',
            url:'/manuals',
            data: {
                search: $('#search-string').val(),
                tag: '{{ request("tag") }}'
            },
            success: function (result) {
                $('#manuals-tbl').html(result);
            }
        });
    }
</script>
@endsection