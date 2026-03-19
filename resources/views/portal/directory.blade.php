@extends('portal.app')

@section('content')
<div class="main-container" style="background-color: #EFF3F6;">
	<div class="section" style="padding-top: 20px !important">
		<div class="container-fluid">
			<div class="col-12 col-xl-10 mx-auto">
				<div class="row">
					<div class="col-6">
						<h1 class="title-2" style="margin: 0; letter-spacing: .5pt; font-size: 20pt; border: 0;">Employee Directory</h1>
					</div>
					<div class="col-2 text-right">
						<p class="d-block text-muted" style="margin-top: 17px; font-style: italic;">Total Employee&nbsp;<span class="badge bg-info" id="total-employee">0</span></p>
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

<!-- Employee Profile Modal -->
<div class="modal fade" id="employeeProfileModal" tabindex="-1" aria-labelledby="employeeProfileModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content" style="border-radius: 0;">
			<div class="modal-header" style="border-bottom: 1px solid rgba(0,0,0,.08);">
				<h5 class="modal-title" id="employeeProfileModalLabel" style="font-weight: 600; letter-spacing: .2pt;">Employee Profile</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" style="background: #EFF3F6;">
				<div id="employeeProfileError" class="alert alert-danger d-none" role="alert" style="margin-bottom: 12px;"></div>

				<div id="employeeProfileLoading" class="text-center" style="padding: 28px 0;">
					<div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
					<div class="text-muted" style="margin-top: 10px;">Loading profile…</div>
				</div>

				<div id="employeeProfileContent" class="d-none">
					<div class="card shadow-sm" style="border: 0; border-radius: 0;">
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-md-4 text-center" style="display: flex; justify-content: center; align-items: center; padding: 18px;">
									<div id="employeeProfileAvatar" style="width: 110px; height: 110px; border-radius: 0; background-size: cover; background-position: center; border: 1px solid rgba(0,0,0,.15); background-color: #fff;"></div>
								</div>
								<div class="col-12 col-md-8" style="padding: 18px;">
									<div id="employeeProfileName" style="font-size: 18pt; font-weight: 700; line-height: 1.2;"></div>
									<div class="text-muted" style="margin-top: 4px;">
										<span id="employeeProfileRole" style="font-weight: 600;"></span>
										<span id="employeeProfileDeptWrap" class="d-none">
											&nbsp;•&nbsp;<span id="employeeProfileDept"></span>
										</span>
									</div>
									<div class="row" style="margin-top: 14px;">
										<div class="col-12 col-sm-6" style="margin-bottom: 10px;">
											<div class="text-muted" style="font-size: 10pt;">Email</div>
											<div id="employeeProfileEmail" style="font-weight: 600;"></div>
										</div>
										<div class="col-12 col-sm-6" style="margin-bottom: 10px;">
											<div class="text-muted" style="font-size: 10pt;">Contact Information</div>
											<div id="employeeProfilePhone" style="font-weight: 600;"></div>
										</div>
										<div class="col-12 col-sm-6" style="margin-bottom: 10px;">
											<div class="text-muted" style="font-size: 10pt;">Employment Status</div>
											<div id="employeeProfileEmploymentStatus" style="font-weight: 600;"></div>
										</div>
										<div class="col-12 col-sm-6" style="margin-bottom: 10px;">
											<div class="text-muted" style="font-size: 10pt;">Tenure</div>
											<div id="employeeProfileTenure" style="font-weight: 600; font-style: italic;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function(){
		var employeeProfileModal = null;
		var activeProfileRequest = null;

		function ensureModal(){
			if (!employeeProfileModal) {
				var modalEl = document.getElementById('employeeProfileModal');
				employeeProfileModal = new bootstrap.Modal(modalEl, {
					backdrop: true,
					keyboard: true
				});
			}
			return employeeProfileModal;
		}

		function setProfileLoading(){
			$('#employeeProfileError').addClass('d-none').text('');
			$('#employeeProfileContent').addClass('d-none');
			$('#employeeProfileLoading').removeClass('d-none');
		}

		function setProfileError(message){
			$('#employeeProfileLoading').addClass('d-none');
			$('#employeeProfileContent').addClass('d-none');
			$('#employeeProfileError').removeClass('d-none').text(message || 'Unable to load profile.');
		}

		function setProfileContent(data){
			$('#employeeProfileLoading').addClass('d-none');
			$('#employeeProfileError').addClass('d-none').text('');

			$('#employeeProfileName').text(data.full_name || 'N/A');
			$('#employeeProfileRole').text(data.job_title || 'N/A');

			if (data.department) {
				$('#employeeProfileDept').text(data.department);
				$('#employeeProfileDeptWrap').removeClass('d-none');
			} else {
				$('#employeeProfileDeptWrap').addClass('d-none');
			}

			$('#employeeProfileEmail').text(data.email || 'N/A');
			$('#employeeProfilePhone').text(data.contact || 'N/A');
			$('#employeeProfileEmploymentStatus').text(data.employment_status || 'N/A');
			$('#employeeProfileTenure').text(data.tenure || 'N/A');

			if (data.avatar_url) {
				$('#employeeProfileAvatar').css('background-image', 'url("' + data.avatar_url + '")');
			} else {
				$('#employeeProfileAvatar').css('background-image', 'none');
			}

			$('#employeeProfileContent').removeClass('d-none');
		}

		function loadEmployeeProfile(userId){
			if (!userId) {
				setProfileError('Invalid employee selected.');
				return;
			}

			setProfileLoading();

			if (activeProfileRequest && activeProfileRequest.readyState !== 4) {
				activeProfileRequest.abort();
			}

			activeProfileRequest = $.ajax({
				type: 'GET',
				url: '/services/directory/profile/' + userId,
				dataType: 'json',
				cache: false,
				success: function (resp) {
					if (resp && resp.success) {
						setProfileContent(resp.data || {});
					} else {
						setProfileError((resp && resp.message) ? resp.message : 'Unable to load profile.');
					}
				},
				error: function (xhr, textStatus) {
					// When logged out, Laravel may return a redirect/login HTML which becomes a JSON parse error.
					if (xhr && (xhr.status === 401 || xhr.status === 302 || textStatus === 'parsererror')) {
						setProfileError('Unable to load profile. Please try again.');
						return;
					}
					if (xhr && xhr.status === 403) {
						setProfileError('You are not authorized to view this profile.');
						return;
					}
					setProfileError('Unable to load profile. Please try again.');
				}
			});
		}

		$(document).on('click', '.employee-profile-link', function (e) {
			e.preventDefault();
			var userId = $(this).data('user-id');
			ensureModal().show();
			loadEmployeeProfile(userId);
		});

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