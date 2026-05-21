<div class="modal fade" id="itineraryNoticeModal" tabindex="-1" aria-labelledby="itineraryNoticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="min-width: 75%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itineraryNoticeModalLabel">Itinerary Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('client.itinerary._form')
            </div>
        </div>
    </div>
</div>

@include('client.itinerary._assets')
