@include('client.itinerary.modals.add_itinerary')
@include('client.itinerary.modals.select_destination')
@include('client.itinerary.modals.select_project')
@include('client.itinerary.modals.select_companion')
@include('client.itinerary.modals.confirm_submission')
@include('client.itinerary.modals.validation_error')

<div class="modal fade" id="portalItineraryAddRowAlert" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Warning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Please fill in all fields.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .portal-itinerary-scroll {
        position: relative;
        height: 320px;
        overflow: auto;
    }
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

    function portalAutoRowNoItr() {
        $('#portal-itinerary-table tbody tr').each(function (idx) {
            $(this).children('th:eq(0)').html(idx + 1);
        });
    }

    function portalAutoRowNoComp() {
        $('#portal-companion-table tbody tr').each(function (idx) {
            $(this).children('th:eq(0)').html(idx + 1);
        });
    }

    function portalSetDestinationList(doctype) {
        $('#portal-destination-list').empty();
        $.ajax({
            url: portalItineraryEndpoints.destinations + doctype,
            method: 'GET',
            success: function (response) {
                var des_sel = '';
                $.each(response, function (i, v) {
                    des_sel += '<tr class="portal-selected-destination" data-id="' + v + '"><td>' + v + '</td></tr>';
                });
                $('#portal-destination-list').append(des_sel);
            }
        });
    }

    function portalInitItineraryForm() {
        if ($('#portal-itinerary-date').data('datepicker')) {
            return;
        }

        $('#portal-itinerary-date').datepicker({
            format: 'mm-dd-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $.ajax({
            url: portalItineraryEndpoints.destinations + 'Project',
            method: 'GET',
            success: function (response) {
                var proj_opt = '';
                $.each(response, function (i, v) {
                    proj_opt += '<tr class="portal-selected-project" data-id="' + v + '"><td>' + v + '</td></tr>';
                });
                $('#portal-project-list').append(proj_opt);
            }
        });

        $.ajax({
            url: portalItineraryEndpoints.employees,
            method: 'GET',
            success: function (response) {
                var com_opt = '';
                $.each(response, function (i, v) {
                    com_opt += '<tr class="portal-selected-companion" data-id="' + v + '" data-name="' + i + '"><td>' + i + '</td></tr>';
                });
                $('#portal-companion-list').append(com_opt);
            }
        });
    }

    function portalResetItineraryForm() {
        $('#portal-itinerary-table tbody').empty();
        $('#portal-companion-table tbody').empty();
        $('#portal-from-select').val('');
        $('#portal-destination').val('');
        $('#portal-itinerary-date').val('');
        $('#portal-itinerary-time').val('');
        $('#portal-project').val('');
        $('#portal-purpose').val('');
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
        $('#portal-validate-itinerary').on('click', function () {
            var rowCount = $('#portal-itinerary-table tbody tr').length;
            if (rowCount <= 0) {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('portalValidationModal')).show();
            } else {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('portalConfirmSubmission')).show();
            }
        });

        $('#portalModalAddItinerary .portal-add-row').on('click', function (e) {
            e.preventDefault();
            var from = $('#portal-from-select').val();
            var destination = $('#portal-destination').val();
            var itinerary_date = $('#portal-itinerary-date').val();
            var itinerary_time = $('#portal-itinerary-time').val();
            var project = $('#portal-project').val();
            var purpose = $('#portal-purpose').val();

            if (from !== '' && destination !== '' && itinerary_date !== '' && itinerary_time !== '' && project !== '' && purpose !== '') {
                var row = '<tr>' +
                    '<th scope="row"></th>' +
                    '<td><input type="hidden" name="from[]" value="' + from + '">' +
                    '<input type="hidden" name="destination[]" value="' + $('<div>').text(destination).html() + '">' +
                    '<input type="hidden" name="itinerary_date[]" value="' + itinerary_date + '">' +
                    '<input type="hidden" name="itinerary_time[]" value="' + itinerary_time + '">' +
                    '<input type="hidden" name="project[]" value="' + $('<div>').text(project).html() + '">' +
                    '<input type="hidden" name="purpose[]" value="' + $('<div>').text(purpose).html() + '">' +
                    destination + '</td>' +
                    '<td>' + itinerary_date + '</td>' +
                    '<td>' + itinerary_time + '</td>' +
                    '<td>' + purpose + '</td>' +
                    '<td><button type="button" class="portal-delete-row btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td></tr>';
                $('#portal-itinerary-table tbody').append(row);
                portalAutoRowNoItr();
                bootstrap.Modal.getOrCreateInstance(document.getElementById('portalModalAddItinerary')).hide();
                $('#portal-from-select').val('');
                $('#portal-destination').val('');
                $('#portal-itinerary-date').val('');
                $('#portal-itinerary-time').val('');
                $('#portal-project').val('');
                $('#portal-purpose').val('');
            } else {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('portalItineraryAddRowAlert')).show();
            }
        });

        $(document).on('click', '#portal-itinerary-table .portal-delete-row, #portal-companion-table .portal-delete-row', function () {
            $(this).closest('tr').remove();
            portalAutoRowNoItr();
            portalAutoRowNoComp();
        });

        $(document).on('change', '#portal-from-select', function () {
            var d = $(this).val();
            $('#portalModalAddItinerary .portal-destination-name').val('');
            if (d !== 'Others') {
                portalSetDestinationList(d);
                $('#portal-destination').prop('readonly', true);
            } else {
                $('#portal-destination-list').empty();
                var des_sel =
                    '<tr class="portal-selected-destination" data-id="Fil-United Plant 1"><td>Fil-United Plant 1</td></tr>' +
                    '<tr class="portal-selected-destination" data-id="FUMACO Plant 2"><td>FUMACO Plant 2</td></tr>' +
                    '<tr class="portal-selected-destination" data-id="FUMACO Showroom"><td>FUMACO Showroom</td></tr>' +
                    '<tr class="portal-selected-destination" data-id="Metrobank"><td>Metrobank</td></tr>';
                $('#portal-destination').prop('readonly', false);
                $('#portal-destination-list').append(des_sel);
            }
        });

        $('#portal-search-destination').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#portal-destination-list tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#portal-search-project').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#portal-project-list tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $('#portal-search-companion').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#portal-companion-list tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        $(document).on('click', '.portal-selected-project', function () {
            $('#portalModalAddItinerary .portal-project-name').val($(this).data('id'));
            bootstrap.Modal.getOrCreateInstance(document.getElementById('portalModalSelectProject')).hide();
        });

        $(document).on('click', '.portal-selected-companion', function () {
            var emp_id = $(this).data('id');
            var emp_name = $(this).data('name');
            var sel = '<tr><th scope="row"></th>' +
                '<td><input type="hidden" name="companion_id[]" value="' + emp_id + '">' +
                '<input type="hidden" name="companion_name[]" value="' + emp_name + '">' + emp_name + '</td>' +
                '<td><button type="button" class="portal-delete-row btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td></tr>';
            $('#portal-companion-table tbody').append(sel);
            portalAutoRowNoComp();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('portalModalSelectCompanion')).hide();
        });

        $(document).on('click', '.portal-selected-destination', function () {
            $('#portalModalAddItinerary .portal-destination-name').val($(this).data('id'));
            bootstrap.Modal.getOrCreateInstance(document.getElementById('portalModalSelectDestination')).hide();
        });

        $(document).on('click', '#portalModalAddItinerary .portal-project-name', function () {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('portalModalSelectProject')).show();
        });

        $(document).on('click', '#portalModalAddItinerary .portal-destination-name', function () {
            var from = $('#portal-from-select').val();
            if (from !== 'Others') {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('portalModalSelectDestination')).show();
            }
        });

        $(document).on('show.bs.modal', '.modal', function () {
            if (!document.getElementById('itineraryNoticeModal')) {
                return;
            }
            var zIndex = 1050 + (10 * $('.modal.show').length);
            $(this).css('z-index', zIndex);
            setTimeout(function () {
                $('.modal-backdrop').not('.modal-stack').last().css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        });

        $('#portal-confirm-itinerary-submit').on('click', function () {
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.ajax({
                url: portalItineraryEndpoints.create,
                type: 'POST',
                data: $('#portal-file-itinerary-form').serialize(),
                success: function (data) {
                    $btn.prop('disabled', false);
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('portalConfirmSubmission')).hide();
                    if (data.success) {
                        if (typeof loadItinerary === 'function') {
                            loadItinerary();
                        }
                        if (typeof showNotification === 'function') {
                            showNotification('fa fa-check-circle-o', data.message, 'success', 'Itinerary submitted');
                        }
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('itineraryNoticeModal')).hide();
                    } else if (typeof showNotification === 'function') {
                        showNotification('fa fa-ban', data.message, 'danger', 'Itinerary');
                    }
                },
                error: function () {
                    $btn.prop('disabled', false);
                    if (typeof showNotification === 'function') {
                        showNotification('fa fa-ban', 'An error occurred. Please try again.', 'danger', 'Itinerary');
                    }
                }
            });
        });
    });
})();
</script>
