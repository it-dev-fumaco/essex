@extends('portal.app')

@section('content')
<div class="main-container bg-white">
	<div class="section" style="padding-top: 20px !important; min-height: 65vh">
		<div class="container-fluid">
			<div class="col-10 mx-auto">
				<div class="row">
					<div class="col-6">
						<h1 class="title-2" style="margin: 0; letter-spacing: .5pt; font-size: 18pt; border: 0;">Email Logs</h1>
					</div>
					<div class="col-4 offset-2" style="padding: 0 !important;">
						<input type="text" id="search-bar" placeholder="Search Logs..." style="box-shadow: 1px 1px 4px rgba(0,0,0,.4); border-radius: 25px; padding: 8px 20px !important; border: 1px solid #EFF3F6; width: 100%;">
					</div>
				</div>
                <div class="row mt-2">
                    <div class="container-fluid" id="logs-container"></div>
                </div>
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

		$(document).on('click', '#logs-pagination a', function(event){
			event.preventDefault();
			var page = $(this).attr('href').split('page=')[1];
			load_tbl(page);
		});

		$(document).on('click', '.resend-email', function (e){
			e.preventDefault();
			var log_id = $(this).data('log-id');
			var btn = $(this);
			$('.spinner-border').removeClass('d-none');
			btn.attr('disabled', true);
			$.ajax({
				type:'GET',
				url: '/resend_email/' + log_id,
				success: function (response) {
					if(response.success){
						showNotification("success", response.message, "fa fa-undo");
						$('.modal').modal('hide');
						load_tbl(1);
					}else{
						showNotification("danger", response.message, "fa fa-undo");
					}
					$('.spinner-border').addClass('d-none');
					btn.attr('disabled', false);
				}
			});
		});
		
		load_tbl(1);
		function load_tbl(page){
			$.ajax({
				type:'GET',
				data: {
					search: $('#search-bar').val(),
					page: page
				},
				url: '/email_logs',
				success: function (response) {
					$('#logs-container').html(response);
				}
			});
		}

		function showNotification(color, message, icon){
            $.notify({
                icon: icon,
                message: message
            },{
                type: color,
                timer: 500,
                z_index: 1060,
                placement: {
					from: 'top',
					align: 'center'
                }
            });
        }
	});
</script>
@endsection