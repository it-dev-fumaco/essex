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
        $('#portal-itinerary-alert').text(message).removeClass('d-none');
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

    function portalToggleDestinationHint(from) {
        $('#portal-destination-hint').toggleClass('d-none', !!from);
    }

    function portalUpdateDestinationField(from) {
        var $select = $('#portal-destination-select');
        var $text = $('#portal-destination-text');
        $select.empty().append('<option value="">--</option>');
        $select.addClass('d-none');
        $text.addClass('d-none').val('');
        portalToggleDestinationHint(from);

        if (!from) {
            return;
        }

        if (from === 'Others') {
            $text.removeClass('d-none').prop('disabled', false);
            return;
        }

        $select.removeClass('d-none').prop('disabled', true);
        $.ajax({
            url: portalItineraryEndpoints.destinations + encodeURIComponent(from),
            method: 'GET',
            success: function (response) {
                $.each(response, function (i, v) {
                    $select.append($('<option>', { value: v, text: v }));
                });
            },
            complete: function () {
                $select.prop('disabled', false);
            }
        });
    }

    function portalClearEntryFields() {
        $('#portal-from-select').val('');
        portalUpdateDestinationField('');
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

    function portalNormalizeItineraryDate(value) {
        var trimmed = (value || '').trim();
        if (!trimmed) {
            return '';
        }

        var match = trimmed.match(/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})(?:\s|$)/);
        if (!match) {
            return trimmed;
        }

        var month = match[1].padStart(2, '0');
        var day = match[2].padStart(2, '0');
        return month + '-' + day + '-' + match[3];
    }

    function portalInitItineraryForm() {
        if (portalItineraryInitialized) {
            portalUpdateDestinationField($('#portal-from-select').val());
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

        portalUpdateDestinationField($('#portal-from-select').val());
    }

    function portalBindItineraryEvents() {
        $(document).on('change', '#portal-from-select', function () {
            portalUpdateDestinationField($(this).val());
        });

        $(document).on('change', '#portal-companion-select', portalSyncCompanionHidden);

        $(document).on('click', '#portal-add-itinerary-row', function () {
            portalHideAlert();
            var from = $('#portal-from-select').val();
            var destination = portalGetDestinationValue();
            var itinerary_date = portalNormalizeItineraryDate($('#portal-itinerary-date').val());
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
                dataType: 'json',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]', this).val() || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                success: function (data) {
                    $btn.prop('disabled', false);
                    if (data.success) {
                        if (typeof loadItinerary === 'function') {
                            loadItinerary();
                        }
                        if (typeof showNotification === 'function') {
                            showNotification('fa fa-check-circle-o', data.message, 'success', 'Itinerary submitted');
                        }
                        var modalEl = document.getElementById('itineraryNoticeModal');
                        if (modalEl) {
                            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                        } else {
                            portalResetItineraryForm();
                        }
                    } else {
                        portalShowAlert(data.message || 'Unable to submit itinerary.');
                    }
                },
                error: function (xhr) {
                    $btn.prop('disabled', false);
                    var message = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.status === 419) {
                        message = 'Your session expired. Please refresh the page and try again.';
                    }
                    portalShowAlert(message);
                }
            });
        });

        var itineraryNoticeModalEl = document.getElementById('itineraryNoticeModal');
        if (itineraryNoticeModalEl) {
            itineraryNoticeModalEl.addEventListener('shown.bs.modal', function () {
                portalInitItineraryForm();
            });
            itineraryNoticeModalEl.addEventListener('hidden.bs.modal', function () {
                portalResetItineraryForm();
            });
        } else if (document.getElementById('portal-file-itinerary-form')) {
            portalInitItineraryForm();
        }
    }

    function portalBootItineraryModule() {
        if (!window.jQuery) {
            return;
        }

        jQuery(portalBindItineraryEvents);
    }

    if (window.jQuery) {
        portalBootItineraryModule();
    } else {
        window.addEventListener('load', portalBootItineraryModule);
    }
})();
</script>
