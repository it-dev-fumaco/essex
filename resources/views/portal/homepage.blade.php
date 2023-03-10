@extends('portal.app')

@section('content')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
	@include('portal.includes.slider')
 	@include('portal.includes.updates')
    @include('portal.includes.events')
    @include('portal.includes.milestones')
    {{-- @include('portal.includes.instructions') --}}
    @if (session()->has('notice_data'))
        @php
            $data = session()->get('notice_data');
        @endphp
        <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="center" style="background-color: {{ $data['success'] == 1 ? '#20BD67;' : '#767F86;' }} padding: 20px 0 20px 0; color: #fff;">
                        <i class="fa fa-check-circle-o" style="font-size: 50pt; font-weight: 500 !important"></i>
                        <br>
                        <h2>{{ $data['status'] ? $data['status'] : null }}</h2>
                    </div>
                    <div class="center" style="padding: 20px 0 20px 0;">
                        <p style="font-size: 12pt;">{!! $data['message'] !!}</p>
                        <br>
                        <button class="btn btn-warning" style="border-radius: 25px;" data-dismiss="modal" aria-label="Close">OK</button>
                    </div>
                </div>
            </div>
            </div>
        </div> 
    @endif
    
@endsection

@section('script')

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script>
        $(document).ready(function () {
            @if (session()->has('notice_data'))
                $('#noticeModal').modal('show');
            @endif
        });
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
    $(document).on('click', '#deletePostBtn', function(event){
        event.preventDefault();
        $('#deletePostModal .post_id').val($(this).data('id'));
        $('#deletePostModal .post_title').text($(this).data('title'));
        $('#deletePostModal').modal('show');
    });
        {{-- $('textarea').ckeditor(); --}}
        // $('.textarea').ckeditor(); // if class is prefered.

        CKEDITOR.config.height = 450;
    </script>
@endsection