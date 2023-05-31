@extends('portal.app')

@section('content')
<div class="main-container" style="background-color: #EFF3F6;">
	<div class="section" style="padding-top: 20px !important">
		<div class="container-fluid">
			<div class="col-md-10 col-md-offset-1">
				<div class="row">
					<div class="col-md-6">
						<h1 class="title-2" style="letter-spacing: .5pt; font-size: 18pt;">Employee Directory</h1>
					</div>
					<div class="col-md-4 col-md-offset-2" style="padding: 0 !important">
						<input type="text" id="search-bar" class="form-control" placeholder="Search Employee..." style=" box-shadow: 1px 1px 4px rgba(0,0,0,.4); border-radius: 25px;">
					</div>
				</div>
				<div id="directory-container" class="row"></div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function(){
		$(document).on('keyup', '#search-bar', function (e){
			e.preventDefault();
			load_tbl();
		});

		load_tbl();
		function load_tbl(){
			$.ajax({
				type:'GET',
				data: {
					search: $('#search-bar').val()
				},
				url: '/services/directory',
				success: function (response) {
					$('#directory-container').html(response);
				}
			});
		}
	});
</script>
@endsection