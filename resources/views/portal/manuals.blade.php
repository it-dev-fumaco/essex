@extends('portal.app')

@section('content')
@include('portal.modals.add_post_modal')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
<div class="main-container">
    <div class="section">
        <div class="container-fluid">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="row">
					<div class="col-6">
						<h1 class="title-2" style="margin: 0; letter-spacing: .5pt; font-size: 18pt; border: 0;">User Manuals</h1>
					</div>
					<div class="col-4 offset-2" style="padding: 0 !important;">
						<input type="text" id="search-string" placeholder="Search Manuals..." style="box-shadow: 1px 1px 4px rgba(0,0,0,.4); border-radius: 25px; padding: 8px 20px !important; border: 1px solid #EFF3F6; width: 100%;">
					</div>
				</div>
    
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissable">
                        <span>{{ session()->get('error') }}</span>
                    </div>
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

    $(document).on('keyup', '#search-string', function (e){
        e.preventDefault();
        load_tbl();
    })

    load_tbl();
    function load_tbl(){
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