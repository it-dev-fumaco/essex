@extends('kiosk.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('kiosk/datepicker/src/anypicker.css') }}" />
<div class="col-md-12 slideInLeft">
	<div class="card mt-3">
		<div class="card-header h3 text-center align-middle">
        <span class="align-middle">Itinerary Form</span>
        <div class="pull-left">
          <a href="/kiosk/itinerary">
                <img src="{{ asset('storage/kiosk/back.png') }}"  width="40" height="40"/>
              </a>
        </div>
        <div class="pull-right">
            <a href="/kiosk/home">
              <img src="{{ asset('storage/kiosk/home-512.png') }}"  width="40" height="40"/>
            </a>
        </div>
        </div>
	  	<div class="card-body">
	  		<form method="POST" action="/kiosk/itinerary/save" id="kiosk-itinerary-form">
	  			@csrf
	    	<div class="row">
	    		<div class="col-md-3">
	    			<div class="form-group">
						<label for="reported-through" class="font-weight-light">Transaction Date</label>
						<input type="text" name="transaction_date" class="form-control" value="{{ date('m-d-Y') }}" readonly>
					</div>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-md-8">
	    			<label class="font-weight-light">Itineraries</label>
	    			<table class="table table-bordered" id="itinerary-table">
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
									<button type="button" class="btn btn-indigo btn-sm m-0" data-toggle="modal" data-target="#modalAddItinerary"><i class="fa fa-plus"></i> Add Itinerary</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="col-md-4">
					<label class="font-weight-light">Companion(s)</label>
	    			<table class="table table-bordered" id="companion-table">
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
									<button type="button" class="btn btn-indigo btn-sm m-0" data-toggle="modal" data-target="#modalSelectCompanion">
										<i class="fa fa-plus"></i> Add Companion
									</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="row">
			    	<div class="col-md-12">
			    		<div class="text-center">
			    			<button type="button" class="btn btn-primary"{{--  data-toggle="modal" data-target="#confirmSubmission" --}} id="validate-frm">
			    				<i class="fa fa-paper-plane-o mr-2"></i>SUBMIT
			    			</button>
			        	</div>
			    	</div>
			    </div>
			    @include('kiosk.itinerary.modals.confirm_submission')
			    @include('kiosk.itinerary.modals.validation_error')
			</form>
	  	</div>
	</div>
</div>

@include('kiosk.itinerary.modals.add_itinerary')
@include('kiosk.itinerary.modals.select_destination')
@include('kiosk.itinerary.modals.select_project')
@include('kiosk.itinerary.modals.select_companion')

<!-- Modal Confirm Submission -->
<div class="modal fade" id="itineraryAddRowAlert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

   <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
   <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Warning</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <h5>Please fill in all fields.</h5>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<!-- Modal Confirm Submission -->

<style type="text/css">
	#itr-time{
		display: none;
	}
</style>

<select id="itr-time">
                     <option value="4:00 AM">4:00 AM</option>
                     <option value="4:30 AM">4:30 AM</option>
                     <option value="5:00 AM">5:00 AM</option>
                     <option value="5:30 AM">5:30 AM</option>
                     <option value="6:00 AM">6:00 AM</option>
                     <option value="6:30 AM">6:30 AM</option>
                     <option value="7:00 AM">7:00 AM</option>
                     <option value="7:30 AM">7:30 AM</option>
                     <option value="8:00 AM">8:00 AM</option>
                     <option value="8:30 AM">8:30 AM</option>
                     <option value="9:00 AM">9:00 AM</option>
                     <option value="9:30 AM">9:30 AM</option>
                     <option value="10:00 AM">10:00 AM</option>
                     <option value="10:30 AM">10:30 AM</option>
                     <option value="11:00 AM">11:00 AM</option>
                     <option value="11:30 AM">11:30 AM</option>
                     <option value="12:00 AM">12:00 AM</option>
                     <option value="12:30 PM">12:30 PM</option>
                     <option value="1:00 PM">1:00 PM</option>
                     <option value="1:30 PM">1:30 PM</option>
                     <option value="2:00 PM">2:00 PM</option>
                     <option value="2:30 PM">2:30 PM</option>
                     <option value="3:00 PM">3:00 PM</option>
                     <option value="3:30 PM">3:30 PM</option>
                     <option value="4:00 PM">4:00 PM</option>
                     <option value="4:30 PM">4:30 PM</option>
                     <option value="5:00 PM">5:00 PM</option>
                     <option value="5:30 PM">5:30 PM</option>
                     <option value="6:00 PM">6:00 PM</option>
                     <option value="6:30 PM">6:30 PM</option>
                     <option value="7:00 PM">7:00 PM</option>
                     <option value="7:30 PM">7:30 PM</option>
                  </select>

                  
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('kiosk/datepicker/src/anypicker.js') }}"></script>
<script>
	$(document).ready(function(){
		$("#itinerary-date").AnyPicker({
			mode: "datetime",
			dateTimeFormat: "MM-dd-yyyy"
		});

		$("#itr-time").AnyPicker({
			mode: "select",
			inputElement: "#itinerary-time"
		});

		var from = $('#from-select').val();

	  	if (from != 'Others') {
      		$('#destination').prop('readonly', true);
	  	}else{
      		$('#destination').prop('readonly', false);
	  	}

		// $("#itinerary-time").AnyPicker({
		// 	mode: "datetime",
		// 	dateTimeFormat: "hh:mm AA"
		// });

		$('#validate-frm').click(function(){
			var rowCount = $('#itinerary-table tbody tr').length;
	         if (rowCount <= 0) {
	         	$('#validationModal').modal('show');
	         }else{
	         	$('#confirmSubmission').modal('show');
	         }
		});

		// append row to itinerary table
		$('#modalAddItinerary .add-row').click(function(e){
         e.preventDefault();
         var from = $('#from-select').val();
         var destination = $('#destination').val();
         var itinerary_date = $('#itinerary-date').val();
         var itinerary_time = $('#itinerary-time').val();
         var project = $('#project').val();
         var purpose = $('#purpose').val();

         var row = '<tr>' +
         	'<th scope="row"></th>' +
         	'<td><input type="hidden" class="form-control" name="from[]" value="' + from + '"><input type="hidden" class="form-control" name="destination[]" value="' + destination + '"><input type="hidden" class="form-control" name="itinerary_date[]" value="' + itinerary_date + '"><input type="hidden" class="form-control" name="itinerary_time[]" value="' + itinerary_time + '"><input type="hidden" class="form-control" name="project[]" value="' + project + '"><input type="hidden" class="form-control" name="purpose[]" value="' + purpose + '">' + destination + '</td>' +
         	'<td>' + itinerary_date + '</td>' +
         	'<td>' + itinerary_time + '</td>' +
         	'<td>' + purpose + '</td>' +
         	'<td><button class="delete btn btn-danger btn-sm m-0"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';

         if (from != "" && destination != "" && itinerary_date != "" && itinerary_time != "" && project != "" && purpose != "") {
         	$("#itinerary-table tbody").append(row);
         	autoRowNoItr();
         	$('#modalAddItinerary').modal('hide');
         }else{
         	$('#itineraryAddRowAlert').modal('show');
         }
      });

      function autoRowNoItr(){
         $('#itinerary-table tbody tr').each(function (idx) {
            $(this).children("th:eq(0)").html(idx + 1);
         });
      }

      function autoRowNoComp(){
         $('#companion-table tbody tr').each(function (idx) {
            $(this).children("th:eq(0)").html(idx + 1);
         });
      }

      $(document).on("click", ".delete", function(){
        	$(this).parents("tr").remove();
        	autoRowNoItr();
        	autoRowNoComp();
      });

      $(document).on('change', '#from-select', function(e){
      	d = $(this).val();
      	$('#modalAddItinerary .destination-name').val('');
      	if (d != 'Others') {
      		setDestinationList(d);
      		$('#destination').prop('readonly', true);
      	}else{
      		$('#destination-list').empty();
      		des_sel = '<tr class="selected-destination" data-id="Fil-United Plant 1">'+
      			'<td>Fil-United Plant 1</td>' +
      			'</tr>' +
      			'<tr class="selected-destination" data-id="FUMACO Plant 2">'+
				'<td>FUMACO Plant 2</td>' +
				'</tr>' +
				'<tr class="selected-destination" data-id="FUMACO Showroom">'+
				'<td>FUMACO Showroom</td>' +
				'<tr class="selected-destination" data-id="Metrobank">'+
				'<td>Metrobank</td>' +
				'</tr>';
      		$('#destination').prop('readonly', false);

			$('#destination-list').append(des_sel);
      	}
      });

      function setDestinationList(doctype){
      	$('#destination-list').empty();
      	
      	$.ajax({
	      	url: "/kiosk/destinations/" + doctype,
				method: "GET",
				success: function(response){
					var des_sel = '';
		      	$.each(response, function(i, v){
		      		des_sel += '<tr class="selected-destination" data-id="' + v + '">'+
							'<td>' + v + '</td>' +
							'</tr>';
		      	});

		      	$('#destination-list').append(des_sel);
				}
	      });
      }

      $.ajax({
      	url: "/kiosk/destinations/Project",
			method: "GET",
			success: function(response){
				var proj_opt = '';
	      	$.each(response, function(i, v){
	      		proj_opt += '<tr class="selected-project" data-id="' + v + '">'+
						'<td>' + v + '</td>' +
						'</tr>';
	      	});

	      	$('#project-list').append(proj_opt);
			}
      });

      $.ajax({
      	url: "/kiosk/employees/erp",
			method: "GET",
			success: function(response){
				var com_opt = '';
	      	$.each(response, function(i, v){
	      		com_opt += '<tr class="selected-companion" data-id="' + v + '" data-name="' + i + '">'+
						'<td>' + i + '</td>' +
						'</tr>';
	      	});

	      	$('#companion-list').append(com_opt);
			}
      });

      $(document).on('show.bs.modal', '.modal', function (event) {
      	var zIndex = 1040 + (10 * $('.modal:visible').length);
         $(this).css('z-index', zIndex);
         setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
         }, 0);
     	});

     	$("#search-destination").on("keyup", function() {
		   	var value = $(this).val().toLowerCase();
		   	$("#destination-list tr").filter(function() {
		   		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		   	});
	  	});

	  	$("#search-project").on("keyup", function() {
		   var value = $(this).val().toLowerCase();
		   $("#project-list tr").filter(function() {
		   	$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		   });
	  	});

	  	$("#search-companion").on("keyup", function() {
		   var value = $(this).val().toLowerCase();
		   $("#companion-list tr").filter(function() {
		   	$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		   });
	  	});

	  	$(document).on('click', '.selected-project', function(){
	  		$('#modalAddItinerary .project-name').val($(this).data('id'));
	  		$('#modalSelectProject').modal('hide');
	  	});

	  	$(document).on('click', '.selected-companion', function(){
	  		var emp_id = $(this).data('id');
	  		var emp_name = $(this).data('name');
	  		// append row to companion
        	var sel = '<tr>' +
      		'<th scope="row"></th>' +
      		'<td><input type="hidden" name="companion_id[]" value="'+emp_id+'"><input type="hidden" name="companion_name[]" value="'+emp_name+'">' + emp_name + '</td>' +
      		'<td><button class="delete btn btn-danger btn-sm m-0"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>' +
				'</tr>';
			
			$("#companion-table tbody").append(sel);
			autoRowNoComp();
			$('#modalSelectCompanion').modal('hide');
	  	});

	  	$(document).on('click', '.selected-destination', function(){
	  		$('#modalAddItinerary .destination-name').val($(this).data('id'));
	  		$('#modalSelectDestination').modal('hide');
	  	});

	  	$(document).on('click', '#modalAddItinerary .project-name', function(){
	  		$('#modalSelectProject').modal('show');
	  	});

	  	$(document).on('click', '#modalAddItinerary .destination-name', function(){
	  		var from = $('#from-select').val();
	  		if (from != 'Others') {
	  			$('#modalSelectDestination').modal('show');
	  		}else{

	  		}
	  		
	  	});
	});
</script>      
@endsection