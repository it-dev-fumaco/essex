@extends('portal.app')
@section('content')
	@include('portal.modals.add_photos_modal')
	@include('portal.modals.set_as_modal')
	
	<section id="content">
		<div class="container">
			<div class="row">
				<div class="heading">
					<div class="section-title text-center">{{ $album->name }}</div>
					<a href="/gallery"><i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-top: -70px; float: left;"></i></a>
				</div>
				@if(Auth::user())
				@if(Auth::user()->user_group == 'Editor')
				<div class="col-md-12">
					<div class="pull-right">
						<div class="form-group">
							<button type="button" class="btn btn-primary btn-sm" id="addPhotos">
								<i class="fa fa-plus"></i><span>Add Photos</span>
							</button>
						</div>
					</div>
				</div>
				@endif
				@endif
			</div>
		</div>
<div class="container-fluid">
	<div class="row">
<div id="portfolio-list">
	@foreach($images as $image)
	<div class="col-md-3 col-sm-6 col-xs-12 mix living bedroom">
		<div class="portfolio-item">
			<img src="{{ asset('storage/'.$image->filepath) }}" alt="" height="350" />
			<div class="overlay">
				<div class="icon">
					@if(Auth::user())
					@if(Auth::user()->user_group == 'Editor')
					<a href="#" id="setAsBtn" data-id="{{ $image->id }}" data-album="{{ $album->id }}" data-album_name="{{ $album->name }}" data-path="{{ $image->filepath }}"><i class="far fa-star"></i></a>
					@endif
					@endif
					<a href="{{ asset('storage/'.$image->filepath) }}" class="lightbox"><i class="icon-eye right"></i></a>
					@if(Auth::user())
					@if(Auth::user()->user_group == 'Editor')
					<a href="#" data-bs-toggle="modal" data-bs-target="#deleteImage{{ $image->id }}"><i class="icon-trash left"></i></a>
					@endif
					@endif
				</div>
			</div>
			<div class="content">
				<h3><a href="#">{{ $image->filename }}</a></h3>
			</div>
		</div>
	</div>
	@include('portal.modals.delete_image_modal')
	@endforeach
</div>
</div>
</div>

</section>
<center>{{ $images->links() }}</center>
@endsection

@section('script')
<script>
$(document).ready(function(){
	$(document).on('click', '#setAsBtn', function(event){
        event.preventDefault();
        $('#setImageAsModal .album_id').val($(this).data('album'));
        $('#setImageAsModal .album_name').text($(this).data('album_name'));
        $('#setImageAsModal .image_path').val($(this).data('path'));
        $('#setImageAsModal').modal('show');
    });

  	$("#addPhotos").click(function(){
		$('#addPhotosModal').modal('show');
	});

    $('#selectedFiles').on('change', function(event){
     var filesLength = $('#selectedFiles')[0].files.length;
     for(var i = 0; i < filesLength; i++){
         $('#gallery').append('<div class="col-md-4"><img src="' + URL.createObjectURL(event.target.files[i]) + '" width="340" height="210" class=\"imageThumb\"></div>');
     }
    });
});
</script>
@endsection