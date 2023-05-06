@extends('portal.app')

@section('content')
@include('portal.modals.add_post_modal')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
<div class="main-container">
    <div class="section">
        <div class="container-fluid">
            <div class="row" style="margin-top: -40px; margin-left: 20px;">
                <div class="col-md-6">
                    <h1 class="title-2" style="border: 0;">User Manuals</h1>
                </div>
                <div class="col-md-4 col-md-offset-2">
                    <input type="text" id="search-string" class="form-control" placeholder="Search Manuals..." style="font-size: 9pt !important; height: 20px;">
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
@endsection

@section('script')

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>

<script>
    $(document).on('keyup', '#search-string', function (e){
        e.preventDefault();
        load_tbl();
    });

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