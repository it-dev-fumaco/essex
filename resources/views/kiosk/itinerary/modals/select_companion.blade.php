<!-- Modal Select Project -->
<div class="modal fade" id="modalSelectCompanion" tabindex="-1" role="dialog" aria-labelledby="modalSelectCompanion" aria-hidden="true">
	<!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
	<div class="modal-dialog modal-md" role="document">
		<form>
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Select Companion</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row mt-2">
					<div class="col-md-12">
						<input type="text" id="search-companion" class="form-control" placeholder="Search..">
						<div class="table-wrapper-scroll-y my-custom-scrollbar">
							<table class="table table-hover">
								<tbody id="companion-list"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				{{-- <button type="button" class="btn btn-primary add-row" data-dismiss="modal">Add</button> --}}
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
		</form>
	</div>
</div>
<!-- Modal Select Project -->


<style type="text/css">
	.my-custom-scrollbar {
		position: relative;
		height: 380px;
		overflow: auto;
	}
	.table-wrapper-scroll-y {
		display: block;
	}
</style>