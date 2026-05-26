<style>
    #itineraryNoticeModal .modal-body {
        max-height: 75vh;
        overflow-y: auto;
    }
</style>

<script>
(function () {
    var portalItineraryEndpoints = {
        destinations: '/itinerary/destinations/',
        employees: '/itinerary/employees',
        create: '/itinerary/create'
    };

    var portalItineraryInitialized = false;

    function portalEscapeHtml(text) {
        return $('<div>').text(text || '').html();
    }

    function portalShowAlert(message) {
        var $alert = $('#portal-itinerary-alert');
        $alert.text(message).removeClass('d-none');
        var modalBody = document.querySelector('#itineraryNoticeModal .modal-body');
        if (modalBody) {
            modalBody.scrollTop = modalBody.scrollHeight;
        }
    }

    function portalHideAlert() {
        $('#portal-itinerary-alert').addClass('d-none').text('');
    }

    function portalAutoRowNoItr() {
        $('#portal-itinerary-table tbody tr').each(function (idx) {
            $(this).children('th:eq(0)').html(idx + 1);
        });
        var hasRows = $('#portal-itinerary-table tbody tr').length > 0;
        $('#portal-itinerary-empty-hint').toggleClass('d-none', hasRows);
    }

    function portalSyncCompanionHidden() {
        var $hidden = $('#portal-companion-hidden');
        $hidden.empty();
        $('#portal-companion-select option:selected').each(function () {
            var id = $(this).val();
            var name = $(this).text();
            $hidden.append('<input type="hidden" name="companion_id[]" value="' + portalEscapeHtml(id) + '">');
            $hidden.append('<input type="hidden" name="companion_name[]" value="' + portalEscapeHtml(name) + '">');
        });
    }

    function portalGetDestinationValue() {
        var from = $('#portal-from-select').val();
        if (from === 'Others') {
            return $('#portal-destination-text').val().trim();
        }
        return $('#portal-destination-select').val();
    }

    function portalClearEntryFields() {
        $('#portal-from-select').val('');
        $('#portal-destination-select').val('').addClass('d-none');
        $('#portal-destination-text').val('').addClass('d-none');
        $('#portal-itinerary-date').val('');
        $('#portal-itinerary-time').val('');
        $('#portal-project').val('');
        $('#portal-purpose').val('');
    }

    function portalResetItineraryForm() {
        $('#portal-itinerary-table tbody').empty();
        $('#portal-companion-select').val([]);
        $('#portal-companion-hidden').empty();
        portalClearEntryFields();
        portalHideAlert();
        portalAutoRowNoItr();
    }

    function portalUpdateDestinationField(from) {
        var $select = $('#portal-destination-select');
        var $text = $('#portal-destination-text');
        $select.empty().append('<option value="">--</option>');
        $select.addClass('d-none');
        $text.addClass('d-none').val('');

        if (!from) {
            return;
        }

        if (from === 'Others') {
            $text.removeClass('d-none');
            return;
        }

        $select.removeClass('d-none');
        $.ajax({
            url: portalItineraryEndpoints.destinations + from,
            method: 'GET',
            success: function (response) {
                $.each(response, function (i, v) {
                    $select.append($('<option>', { value: v, text: v }));
                });
            }
        });
    }

    function portalInitItineraryForm() {
        if (portalItineraryInitialized) {
            return;
        }
        portalItineraryInitialized = true;

        if (!$('#portal-itinerary-date').data('datepicker')) {
            $('#portal-itinerary-date').datepicker({
                format: 'mm-dd-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        }

        $.ajax({
            url: portalItineraryEndpoints.destinations + 'Project',
            method: 'GET',
            success: function (response) {
                var $project = $('#portal-project');
                $project.find('option:not(:first)').remove();
                $.each(response, function (i, v) {
                    $project.append($('<option>', { value: v, text: v }));
                });
            }
        });

        $.ajax({
            url: portalItineraryEndpoints.employees,
            method: 'GET',
            success: function (response) {
                var $companion = $('#portal-companion-select');
                $companion.empty();
                $.each(response, function (name, id) {
                    $companion.append($('<option>', { value: id, text: name }));
                });
            }
        });
    }

    var itineraryNoticeModalEl = document.getElementById('itineraryNoticeModal');
    if (itineraryNoticeModalEl) {
        itineraryNoticeModalEl.addEventListener('shown.bs.modal', function () {
            portalInitItineraryForm();
        });
        itineraryNoticeModalEl.addEventListener('hidden.bs.modal', function () {
            portalResetItineraryForm();
        });
    }

    $(document).ready(function () {
        $(document).on('change', '#portal-from-select', function () {
            portalUpdateDestinationField($(this).val());
        });

        $(document).on('change', '#portal-companion-select', portalSyncCompanionHidden);

        $('#portal-add-itinerary-row').on('click', function () {
            portalHideAlert();
            var from = $('#portal-from-select').val();
            var destination = portalGetDestinationValue();
            var itinerary_date = $('#portal-itinerary-date').val();
            var itinerary_time = $('#portal-itinerary-time').val();
            var project = $('#portal-project').val();
            var purpose = $('#portal-purpose').val();

            if (!from || !destination || !itinerary_date || !itinerary_time || !project || !purpose) {
                portalShowAlert('Please fill in all required fields before adding to the list.');
                return;
            }

            var row = '<tr>' +
                '<th scope="row"></th>' +
                '<td><input type="hidden" name="from[]" value="' + portalEscapeHtml(from) + '">' +
                '<input type="hidden" name="destination[]" value="' + portalEscapeHtml(destination) + '">' +
                '<input type="hidden" name="itinerary_date[]" value="' + portalEscapeHtml(itinerary_date) + '">' +
                '<input type="hidden" name="itinerary_time[]" value="' + portalEscapeHtml(itinerary_time) + '">' +
                '<input type="hidden" name="project[]" value="' + portalEscapeHtml(project) + '">' +
                '<input type="hidden" name="purpose[]" value="' + portalEscapeHtml(purpose) + '">' +
                portalEscapeHtml(destination) + '</td>' +
                '<td>' + portalEscapeHtml(itinerary_date) + '</td>' +
                '<td>' + portalEscapeHtml(itinerary_time) + '</td>' +
                '<td>' + portalEscapeHtml(purpose) + '</td>' +
                '<td><button type="button" class="portal-delete-row btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td></tr>';

            $('#portal-itinerary-table tbody').append(row);
            portalAutoRowNoItr();
            portalClearEntryFields();
        });

        $(document).on('click', '#portal-itinerary-table .portal-delete-row', function () {
            $(this).closest('tr').remove();
            portalAutoRowNoItr();
        });

        $('#portal-file-itinerary-form').on('submit', function (e) {
            e.preventDefault();
            portalHideAlert();
            portalSyncCompanionHidden();

            if ($('#portal-itinerary-table tbody tr').length <= 0) {
                portalShowAlert('Please add at least one itinerary stop.');
                return;
            }

            if (!confirm('Submit itinerary slip for approval?')) {
                return;
            }

            var $btn = $('#portal-submit-itinerary');
            $btn.prop('disabled', true);

            $.ajax({
                url: portalItineraryEndpoints.create,
                type: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    $btn.prop('disabled', false);
                    if (data.success) {
                        if (typeof loadItinerary === 'function') {
                            loadItinerary();
                        }
                        if (typeof showNotification === 'function') {
                            showNotification('fa fa-check-circle-o', data.message, 'success', 'Itinerary submitted');
                        }
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('itineraryNoticeModal')).hide();
                    } else {
                        portalShowAlert(data.message || 'Unable to submit itinerary.');
                    }
                },
                error: function () {
                    $btn.prop('disabled', false);
                    portalShowAlert('An error occurred. Please try again.');
                }
            });
        });
    });
})();
</script>
