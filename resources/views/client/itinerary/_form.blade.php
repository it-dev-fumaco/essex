<p class="text-muted mb-3" style="font-size: 12px;">File an itinerary slip for approval without using the kiosk.</p>
<form id="portal-file-itinerary-form">
    @csrf
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="form-label fw-bold" style="font-size: 12px;">Transaction Date</label>
            <input type="text" class="form-control form-control-sm" value="{{ date('m-d-Y') }}" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <label class="fw-bold" style="font-size: 12px;">Itineraries</label>
            <table class="table table-bordered table-sm" id="portal-itinerary-table" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Location</th>
                        <th scope="col">Itinerary Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Purpose</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#portalModalAddItinerary">
                                <i class="fa fa-plus"></i> Add Itinerary
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-lg-4">
            <label class="fw-bold" style="font-size: 12px;">Companion(s)</label>
            <table class="table table-bordered table-sm" id="portal-companion-table" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Employee Name</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#portalModalSelectCompanion">
                                <i class="fa fa-plus"></i> Add Companion
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="text-center mt-3">
        <button type="button" class="btn btn-primary" id="portal-validate-itinerary">
            <i class="fa fa-paper-plane-o me-1"></i> Submit for Approval
        </button>
    </div>
</form>
