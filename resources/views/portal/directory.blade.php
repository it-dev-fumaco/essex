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

<style>
	.directory-department-heading {
		margin-top: 18px;
		text-align: center;
		text-transform: uppercase;
	}

	.directory-department-heading h3 {
		margin: 0;
		color: #1f2a44;
		font-size: 18px;
		letter-spacing: 1px;
		font-weight: 700;
	}

	.directory-card-col {
		padding: 10px;
	}

	.directory-card {
		display: flex;
		align-items: center;
		gap: 12px;
		height: 100%;
		padding: 14px;
		border-radius: 12px;
		border: 1px solid #dfe6ef;
		background: #fff;
		box-shadow: 0 2px 8px rgba(16, 24, 40, 0.08);
		transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
		text-decoration: none;
		color: inherit;
	}

	.directory-card:hover,
	.directory-card:focus {
		text-decoration: none;
		color: inherit;
		transform: translateY(-1px);
		border-color: #c8d8ea;
		box-shadow: 0 6px 14px rgba(16, 24, 40, 0.12);
	}

	.directory-card__avatar-wrap {
		flex: 0 0 74px;
	}

	.directory-card__avatar {
		width: 74px;
		height: 74px;
		border-radius: 10px;
		border: 1px solid #d0dae8;
		background: #f6f8fb center/cover no-repeat;
	}

	.directory-card__body {
		min-width: 0;
	}

	.directory-card__name {
		font-weight: 700;
		font-size: 18px;
		line-height: 1.2;
		color: #1f2a44;
		word-break: break-word;
	}

	.directory-card__role {
		margin-top: 3px;
		font-size: 14px;
		font-weight: 500;
		color: #667085;
		word-break: break-word;
	}

	.directory-card__line {
		margin-top: 8px;
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 13px;
		line-height: 1.35;
		color: #2d3b52;
	}

	.directory-card__line i {
		width: 14px;
		color: #4a77a8;
	}

	.directory-card__line span {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.employee-profile-modal .modal-dialog {
		max-width: 640px;
		margin: 1.25rem auto;
	}

	.employee-profile-modal .modal-content {
		border-radius: 16px;
		border: 1px solid rgba(16, 24, 40, 0.08);
		box-shadow: 0 12px 40px rgba(16, 24, 40, 0.12);
		overflow: hidden;
	}

	.employee-profile-modal .modal-header {
		padding: 16px 22px;
		border-bottom: 1px solid #e8ecf2;
		background: #fff;
	}

	.employee-profile-modal .modal-title {
		font-size: 18px;
		font-weight: 700;
		color: #0f172a;
		letter-spacing: -0.02em;
	}

	.employee-profile-modal .modal-body {
		background: #fff;
		padding: 22px 24px 26px;
	}

	.employee-profile-panel {
		background: #fff;
		border: 1px solid #e8ecf2;
		border-radius: 14px;
		padding: 22px 24px;
		box-shadow: 0 1px 3px rgba(16, 24, 40, 0.06);
	}

	.employee-profile-summary {
		display: flex;
		gap: 18px;
		align-items: flex-start;
		padding-bottom: 20px;
		margin-bottom: 0;
		border-bottom: 1px solid #eef1f6;
	}

	.employee-profile-avatar {
		width: 108px;
		height: 108px;
		border: 1px solid #e2e8f0;
		border-radius: 12px;
		background: #f8fafc center/cover no-repeat;
		flex: 0 0 auto;
		box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
	}

	.employee-profile-summary-text {
		min-width: 0;
		flex: 1;
	}

	.employee-profile-name-row {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 10px 12px;
	}

	.employee-profile-name {
		font-size: 22px;
		line-height: 1.25;
		font-weight: 700;
		color: #0f172a;
		letter-spacing: -0.02em;
		word-break: break-word;
	}

	.employee-profile-meta {
		margin-top: 6px;
		font-size: 14px;
		font-weight: 500;
		color: #64748b;
		line-height: 1.45;
		word-break: break-word;
	}

	.employee-profile-email-row {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		margin-top: 12px;
		font-size: 14px;
		font-weight: 500;
		color: #334155;
		line-height: 1.45;
	}

	.employee-profile-email-row i {
		margin-top: 3px;
		color: #64748b;
		font-size: 15px;
		flex-shrink: 0;
	}

	.employee-profile-email-row span {
		word-break: break-word;
		overflow-wrap: anywhere;
	}

	.employee-profile-split {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 0;
		margin-top: 20px;
		padding-top: 20px;
		border-top: 1px solid #eef1f6;
	}

	.employee-profile-col {
		padding: 0 20px 0 0;
		min-width: 0;
	}

	.employee-profile-col + .employee-profile-col {
		padding: 0 0 0 20px;
		border-left: 1px solid #eef1f6;
	}

	.employee-profile-col-title {
		font-size: 11px;
		font-weight: 800;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		color: #475569;
		margin: 0 0 12px;
	}

	.employee-profile-contact-line,
	.employee-profile-emp-line {
		display: flex;
		align-items: flex-start;
		gap: 10px;
		font-size: 15px;
		font-weight: 600;
		color: #1e293b;
		line-height: 1.45;
	}

	.employee-profile-contact-line i,
	.employee-profile-emp-line > i {
		color: #64748b;
		margin-top: 2px;
		flex-shrink: 0;
		font-size: 15px;
	}

	.employee-profile-emp-line + .employee-profile-emp-line {
		margin-top: 12px;
	}

	.employee-profile-emp-line--tenure {
		flex-wrap: wrap;
		align-items: center;
	}

	.employee-profile-tenure-label {
		font-weight: 700;
		color: #475569;
		margin-right: 6px;
	}

	.employee-profile-value {
		font-size: 15px;
		font-weight: 600;
		color: #1e293b;
		line-height: 1.45;
		word-break: break-word;
		overflow-wrap: anywhere;
	}

	.employee-profile-tenure-value {
		font-style: normal;
		font-weight: 600;
		color: #0f172a;
	}

	.employee-profile-status-pill {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 12px;
		border-radius: 999px;
		font-size: 13px;
		font-weight: 700;
		line-height: 1.2;
	}

	.employee-profile-status-pill i {
		font-size: 13px;
	}

	.employee-profile-status-pill.is-regular {
		background: #e4f8f2;
		color: #1f8a66;
	}

	.employee-profile-status-pill.is-probationary {
		background: #fff5df;
		color: #b87407;
	}

	.employee-profile-status-pill.is-other {
		background: #edf2f7;
		color: #3b4a60;
	}

	@media (max-width: 991.98px) {
		.employee-profile-modal .modal-dialog {
			max-width: calc(100% - 1.5rem);
		}

		.employee-profile-name {
			font-size: 20px;
		}

		.employee-profile-meta {
			font-size: 13px;
		}

		.employee-profile-value {
			font-size: 14px;
		}

		.employee-profile-status-pill {
			font-size: 12px;
			padding: 4px 10px;
		}
	}

	@media (max-width: 767.98px) {
		.employee-profile-modal .modal-dialog {
			max-width: calc(100% - 1rem);
			margin: 0.5rem auto;
		}

		.employee-profile-modal .modal-header {
			padding: 12px 16px;
		}

		.employee-profile-modal .modal-body {
			padding: 16px;
		}

		.employee-profile-panel {
			padding: 16px 18px;
		}

		.employee-profile-summary {
			flex-direction: column;
			align-items: flex-start;
		}

		.employee-profile-split {
			grid-template-columns: 1fr;
			padding-top: 16px;
			margin-top: 16px;
		}

		.employee-profile-col {
			padding: 0 !important;
		}

		.employee-profile-col:first-child {
			padding-bottom: 4px !important;
		}

		.employee-profile-col + .employee-profile-col {
			padding: 16px 0 0 !important;
			border-left: 0;
			border-top: 1px solid #eef1f6;
		}
	}

	@media (max-width: 575.98px) { }
</style>

<!-- Employee Profile Modal -->
<div class="modal fade employee-profile-modal" id="employeeProfileModal" tabindex="-1" aria-labelledby="employeeProfileModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="employeeProfileModalLabel">Employee Profile</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="employeeProfileError" class="alert alert-danger d-none" role="alert" style="margin-bottom: 12px;"></div>

				<div id="employeeProfileLoading" class="text-center" style="padding: 28px 0;">
					<div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
					<div class="text-muted" style="margin-top: 10px;">Loading profile…</div>
				</div>

				<div id="employeeProfileContent" class="d-none">
					<div class="employee-profile-panel">
						<div class="employee-profile-summary">
							<div id="employeeProfileAvatar" class="employee-profile-avatar" role="img" aria-label="Profile photo"></div>
							<div class="employee-profile-summary-text">
								<div class="employee-profile-name-row">
									<span id="employeeProfileName" class="employee-profile-name"></span>
									<span id="employeeProfileStatusPill" class="employee-profile-status-pill is-other">
										<i class="fa fa-check-circle" aria-hidden="true"></i>
										<span id="employeeProfileEmploymentStatus"></span>
									</span>
								</div>
								<div class="employee-profile-meta">
									<span id="employeeProfileRole"></span>
									<span id="employeeProfileDeptWrap" class="d-none">
										&nbsp;•&nbsp;<span id="employeeProfileDept"></span>
									</span>
								</div>
								<div class="employee-profile-email-row">
									<i class="fas fa-envelope" aria-hidden="true"></i>
									<span id="employeeProfileEmail"></span>
								</div>
							</div>
						</div>
						<div class="employee-profile-split">
							<div class="employee-profile-col employee-profile-col--contact">
								<h3 class="employee-profile-col-title">Contact</h3>
								<div class="employee-profile-contact-line">
									<i class="fa fa-phone" aria-hidden="true"></i>
									<span id="employeeProfilePhone" class="employee-profile-value"></span>
								</div>
							</div>
							<div class="employee-profile-col employee-profile-col--employment">
								<h3 class="employee-profile-col-title">Employment</h3>
								<div class="employee-profile-emp-line employee-profile-emp-line--tenure">
									<i class="fa fa-hourglass-half" aria-hidden="true"></i>
									<span class="employee-profile-tenure-label">Tenure</span>
									<span id="employeeProfileTenure" class="employee-profile-value employee-profile-tenure-value"></span>
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
			var email = data.email || 'N/A';
			var phone = data.contact || 'N/A';
			var status = data.employment_status || 'N/A';
			var tenure = data.tenure || 'N/A';

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

			$('#employeeProfileEmail').text(email);
			$('#employeeProfilePhone').text(phone);
			$('#employeeProfileEmploymentStatus').text(status);
			$('#employeeProfileEmploymentStatusText').text(status);
			$('#employeeProfileTenure').text(tenure);

			var normalizedStatus = (status || '').toString().toLowerCase();
			var statusPill = $('#employeeProfileStatusPill');
			statusPill.removeClass('is-regular is-probationary is-other');
			if (normalizedStatus === 'regular') {
				statusPill.addClass('is-regular');
			} else if (normalizedStatus === 'probationary') {
				statusPill.addClass('is-probationary');
			} else {
				statusPill.addClass('is-other');
			}

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
			if (window.__directorySearchTimer) {
				clearTimeout(window.__directorySearchTimer);
			}
			window.__directorySearchTimer = setTimeout(function () {
				load_tbl(1);
			}, 250);
		});

		$(document).on('click', '#directory-container .pagination a', function (e) {
			e.preventDefault();
			var href = $(this).attr('href') || '';
			var match = href.match(/[?&]page=(\d+)/);
			var page = match ? parseInt(match[1], 10) : 1;
			load_tbl(page);
		});

		load_tbl(1);
		function load_tbl(page){
			$.ajax({
				type:'GET',
				dataType: 'json',
				data: {
					search: $('#search-bar').val(),
					page: page
				},
				url: '/services/directory',
				success: function (response) {
					if (response && response.html !== undefined) {
						$('#directory-container').html(response.html);
					}
					if (response && response.total !== undefined) {
						$('#total-employee').text(response.total);
					}
				}
			});
		}
	});
</script>
@endsection