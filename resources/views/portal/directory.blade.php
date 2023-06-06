@extends('portal.app')

@section('content')
<div class="main-container" style="background-color: #EFF3F6;">
	<div class="section" style="padding-top: 20px !important">
		<div class="container-fluid">
			<div class="col-10 mx-auto">
				<div class="row">
					<div class="col-6">
						<h1 class="title-2" style="margin: 0; letter-spacing: .5pt; font-size: 18pt; border: 0;">Employee Directory</h1>
					</div>
					<div class="col-2 text-right">
						<p class="d-block text-muted" style="margin-top: 12px; font-style: italic;">Total&nbsp;<span class="badge bg-info" id="total-employee">0</span></p>
					</div>
					<div class="col-4" style="padding: 0 !important;">
						<input type="text" id="search-bar" placeholder="Search Employee..." style="box-shadow: 1px 1px 4px rgba(0,0,0,.4); border-radius: 25px; padding: 8px 20px !important; border: 1px solid #EFF3F6; width: 100%;">
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

			$.ajax({
				type:'GET',
				data: {
					search: $('#search-bar').val(),
					total: 1,
				},
				url: '/services/directory',
				success: function (response) {
					$('#total-employee').text(response);
				}
			});
		}
	});
</script>
@endsection