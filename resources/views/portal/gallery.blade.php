@extends('portal.app')
@section('content')
@include('portal.modals.add_album_modal')
@include('portal.modals.edit_album_modal')
@include('portal.modals.delete_album_modal')
<div class="col-10 mx-auto">
	<div class="row">
		<div class="similar-properties">
			<div class="heading" style="margin-top: 2%;">
				<div class="section-title text-center">Our Gallery</div>
			</div>

			<div class="col-md-12 col-md-6 col-md-offset-3 border border-danger">
				<select class="form-control" id="selectActivity">
					<option value="">All Activities</option>
					@foreach($activity_types as $type)
					<option value="{{ $type->id }}">{{ $type->activity_name }}</option>
					@endforeach
				</select>
			</div>
			@if(Auth::user())
			@if(in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
			<div class="col-md-12 mt-3">
				<div class="pull-right">
					<div class="form-group">
						<button type="button" class="btn btn-primary btn-sm" id="addAlbum">
							<i class="fa fa-plus"></i><span>Album</span>
						</button>
					</div>
				</div>
			</div>
			@endif
			@endif
			<div class="col-md-12" style="margin-top: 2%;">
				<div id="album-list"></div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function(){
    $("#addAlbum").click(function(){
        $('#addAlbumModal').modal('show');
    });

   loadAlbums();

	function loadAlbums(page){
		var activity = $('#selectActivity').val();

		$.ajax({
			url: "/gallery/fetchAlbums?page="+page,
			data: {'activity': activity},
			success: function(data){
				$('#album-list').html(data);
			}
		});
	}

	$(document).on('click', '#album_pagination a', function(e){
		e.preventDefault();
		var page = $(this).attr('href').split('page=')[1];
		loadAlbums(page);
	});

	$('#selectActivity').on("change", function(e){
		e.preventDefault();
		loadAlbums();
	});

	$(document).on("click", '#editAlbumBtn', function(e){
		e.preventDefault();
		$('#editAlbumModal .album_id').val($(this).data('id'));
		$('#editAlbumModal .activity_type').val($(this).data('activity'));
		$('#editAlbumModal .album_name').val($(this).data('album'));
		$('#editAlbumModal .description').val($(this).data('description'));
		$('#editAlbumModal').modal('show');
	});

	$(document).on("click", '#deleteAlbumBtn', function(e){
		e.preventDefault();
		$('#deleteAlbumModal .album_id').val($(this).data('id'));
		$('#deleteAlbumModal .album_name').text($(this).data('album'));
		$('#deleteAlbumModal').modal('show');
	});
});
</script>


@endsection