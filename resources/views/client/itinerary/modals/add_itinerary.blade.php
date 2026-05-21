<div class="modal fade" id="portalModalAddItinerary" tabindex="-1" aria-labelledby="portalModalAddItineraryLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="portalModalAddItineraryLabel">Add Itinerary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="portal-from-select">From</label>
                        <select class="form-select form-select-sm" id="portal-from-select">
                            <option value="">--</option>
                            <option value="Lead">Lead</option>
                            <option value="Customer">Customer</option>
                            <option value="Supplier">Supplier</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="portal-destination">Destination</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control portal-destination-name" id="portal-destination" placeholder="Enter Destination">
                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#portalModalSelectDestination">Select</button>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label>Itinerary Date &amp; Time</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="portal-itinerary-date" placeholder="MM-DD-YYYY" autocomplete="off">
                            <select class="form-select" id="portal-itinerary-time" style="max-width: 140px;">
                                <option value="">Time</option>
                                @foreach(['4:00 AM','4:30 AM','5:00 AM','5:30 AM','6:00 AM','6:30 AM','7:00 AM','7:30 AM','8:00 AM','8:30 AM','9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','12:00 PM','12:30 PM','1:00 PM','1:30 PM','2:00 PM','2:30 PM','3:00 PM','3:30 PM','4:00 PM','4:30 PM','5:00 PM','5:30 PM','6:00 PM','6:30 PM','7:00 PM','7:30 PM'] as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="portal-project">Project</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control portal-project-name" id="portal-project" placeholder="Tap to select" readonly>
                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#portalModalSelectProject">Select</button>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label for="portal-purpose">Purpose</label>
                        <textarea class="form-control form-control-sm" id="portal-purpose" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm portal-add-row">Add</button>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
