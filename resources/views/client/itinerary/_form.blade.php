<form id="portal-file-itinerary-form">
    @csrf
    <div class="row m-0 p-3">
        <div class="col-md-8">
            <span class="d-block fw-bold fs-6 mb-3">Create new itinerary</span>

            <div class="row mb-2">
                <div class="col-sm-6" style="padding: 0 0 10px 15px;">
                    <span class="d-block fst-italic">Transaction Date</span>
                    <input type="text" value="{{ date('m-d-Y') }}" readonly style="width: 160px;">
                </div>
            </div>

            <div class="portal-itinerary-entry border rounded p-3 mb-3" style="background: #f8fafc;">
                <div class="row">
                    <div class="col-sm-6" style="padding: 10px 0 10px 15px;">
                        <span class="d-block fst-italic">From*</span>
                        <select id="portal-from-select" style="width: 100%; max-width: 260px;">
                            <option value="">--</option>
                            <option value="Lead">Lead</option>
                            <option value="Customer">Customer</option>
                            <option value="Supplier">Supplier</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="col-sm-6" style="padding: 10px 0;">
                        <span class="d-block fst-italic">Destination*</span>
                        <p id="portal-destination-hint" class="text-muted small mb-1">Select a From type first.</p>
                        <select id="portal-destination-select" class="d-none" style="width: 100%; max-width: 260px;">
                            <option value="">--</option>
                        </select>
                        <input type="text" id="portal-destination-text" class="d-none" placeholder="Enter destination" style="width: 100%; max-width: 260px;">
                    </div>
                    <div class="col-sm-6" style="padding: 10px 0 10px 15px;">
                        <span class="d-block fst-italic">Itinerary Date*</span>
                        <input type="text" id="portal-itinerary-date" placeholder="MM-DD-YYYY" autocomplete="off" style="width: 160px;">
                    </div>
                    <div class="col-sm-6" style="padding: 10px 0;">
                        <span class="d-block fst-italic">Time*</span>
                        <select id="portal-itinerary-time" style="width: 200px;">
                            <option value="">--</option>
                            @foreach(['4:00 AM','4:30 AM','5:00 AM','5:30 AM','6:00 AM','6:30 AM','7:00 AM','7:30 AM','8:00 AM','8:30 AM','9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','12:00 PM','12:30 PM','1:00 PM','1:30 PM','2:00 PM','2:30 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM','5:00 PM','5:30 PM','6:00 PM','6:30 PM','7:00 PM','7:30 PM'] as $slot)
                            <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6" style="padding: 10px 0 10px 15px;">
                        <span class="d-block fst-italic">Project*</span>
                        <select id="portal-project" style="width: 100%; max-width: 260px;">
                            <option value="">--</option>
                        </select>
                    </div>
                    <div class="col-sm-12" style="padding: 10px 0 10px 15px;">
                        <span class="d-block fst-italic" style="vertical-align: top;">Purpose*</span>
                        <textarea id="portal-purpose" rows="3" cols="50"></textarea>
                    </div>
                </div>
                <div class="text-end" style="padding-right: 15px;">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="portal-add-itinerary-row">
                        <i class="fa fa-plus"></i> Add to list
                    </button>
                </div>
            </div>

            <label class="fw-bold mb-2" style="font-size: 13px;">Itinerary stops</label>
            <div class="table-responsive mb-2">
                <table class="table table-bordered table-sm mb-0" id="portal-itinerary-table" style="font-size: 12px;">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 36px;">#</th>
                            <th scope="col">Location</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Purpose</th>
                            <th scope="col" style="width: 44px;"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <p id="portal-itinerary-empty-hint" class="text-muted small mb-0">Add at least one stop using the form above.</p>
        </div>

        <div class="col-md-4" style="padding: 50px 0 20px 30px;">
            <span class="fst-italic d-block" style="padding-bottom: 10px;">Companion(s)</span>
            <p class="text-muted small mb-2">Optional — hold Ctrl (Windows) or Cmd (Mac) to select multiple.</p>
            <select id="portal-companion-select" multiple size="12" style="width: 100%; min-height: 220px;"></select>
            <div id="portal-companion-hidden"></div>
        </div>

        <div class="col-sm-12 center mb-3">
            <div id="portal-itinerary-alert" class="alert alert-danger d-none mb-3" role="alert"></div>
            <button type="submit" class="btn btn-primary" id="portal-submit-itinerary">
                <i class="fa fa-check"></i> Request for Approval
            </button>
        </div>
    </div>
</form>
