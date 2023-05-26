@extends('portal.app')

@section('content')
<div class="main-container" style="background-color: #EFF3F6;">
	<div class="section">
		<div class="container-fluid">
			<h1 class="title-2 center" style="margin: -40px 0 0 0; letter-spacing: .5pt; font-size: 18pt;">Employee Directory</h1>
			<div class="col-md-10 col-md-offset-1">
				<div class="row">
					@foreach ($employees as $department => $employee)
						<div class="col-md-12 text-center" style="margin-top: 20px;">
							<h3>{{ $department }}</h3>
						</div>
						@foreach ($employee as $emp)
							<div class="col-md-3" style="padding: 10px;">
								<div class="card" style="background-color: #fff; box-shadow: 1px 1px 4px rgba(0,0,0,.4)">
									<div class="row" style="min-height: 110px">
										<div class="col-md-4" style="padding-top: 10px;">
											@php
												$image = $emp->image ? $emp->image : 'storage/img/user.png';
												if(!Storage::disk('public')->exists(str_replace('storage/', null, $image))){
													$image = 'storage/img/user.png';
												}
											@endphp
											<div class="profile-image" style="background-image: url({{ asset($image) }}); border: 1px solid rgba(0,0,0,.3)"></div>
										</div>
										<div class="col-md-8" style="padding-left: 0;">
											<span style="font-weight: 600; font-size: 12pt;">{{ $emp->employee_name }}</span><br/>
											<span class="text-muted">{{ $emp->designation }}</span><br/>
											@if ($emp->email)
												<small style="white-space: nowrap !important"><i class="fa fa-envelope"></i>&nbsp;{{ $emp->email }}</small>
											@endif
											@if ($emp->telephone)
												<br/>
												<small style="white-space: nowrap !important"><i class="fa fa-phone"></i>&nbsp;{{ $emp->telephone }}</small>
											@endif
										</div>
									</div>
								</div>
							</div>
						@endforeach
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.
</style>
	
@endsection

@section('script')
<script>
$(document).ready(function(){

});
</script>
@endsection