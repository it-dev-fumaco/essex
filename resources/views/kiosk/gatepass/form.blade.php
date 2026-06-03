@extends('kiosk.app')
@section('metawebapp')
<meta name="apple-mobile-web-app-capable" content="yes">
@stop
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('kiosk/datepicker/src/anypicker.css') }}" />
<div class="col-md-12 slideInLeft">
<div class="card mt-3" style="height: 97%;">
   <div class="card-header h3 text-center">
    <span class="align-middle">Gatepass Request Form</span>
        
        <div class="pull-left">
          <a href="#" onclick="cancel_button()">
                <img src="{{ asset('storage/kiosk/back.png') }}"  width="40" height="40"/>
              </a>
        </div>
        <div class="pull-right">
          <a href="#" onclick="refresh()" id="refresh">
            <img src="{{ asset('storage/refresh.png') }}"  width="40" height="40"/>
          </a>
            <a href="#" data-toggle="modal" data-target="#confirmHome">
              <img src="{{ asset('storage/kiosk/home-512.png') }}"  width="40" height="40"/>
            </a>
        </div>
    </div>
    <div class="card-body">
      <p class="card-text text-left">Please fill in the necessary fields.</p>
      <form id="add-gatepass-form" method="POST" action="/kiosk/gatepass/form/insert">
      @csrf
      <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
        <input type="hidden" name="department" value="{{ Auth::user()->department_id }}">
      <div class="row" style="margin-top: -40px;">
        <div class="col-md-6 pt-4">
          <div class="row" id="datepairExample">
            <div class="col-md-8">
              <div class="form-group">
                <label for="from-date" class="grey-text font-weight-light">Return On</label>
                <input type="text" aria-label="From Date" class="form-control date" id="returned_on" name="returned_on" autocomplete="off">
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="to-date" class="grey-text font-weight-light">Time</label>
                <input type="text" aria-label="Time" class="form-control time" id="time" name="time" autocomplete="off">
                
            </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="grey-text font-weight-light">Purpose Type</label>
                <!-- Group of default radios - option 1 -->
              <div class="custom-control custom-radio mt-2 pl-5">
                  <input type="radio" class="custom-control-input purposetype" id="defaultGroupExample1" name="purpose_type" value="For Servicing" checked>
                  <label class="custom-control-label" for="defaultGroupExample1">For Servicing</label>
              </div>

              <!-- Group of default radios - option 2 -->
              <div class="custom-control custom-radio mt-3 pl-5">
                  <input type="radio" name="purpose_type" class="custom-control-input purposetype" value="For Company Activity" id="defaultGroupExample2">
                  <label class="custom-control-label" for="defaultGroupExample2">For Company Activity</label>
              </div>

              <!-- Group of default radios - option 3 -->
              <div class="custom-control custom-radio mt-3 pl-5">
                  <input type="radio" name="purpose_type" class="custom-control-input purposetype" value="For Personal Use" id="defaultGroupExample3">
                  <label class="custom-control-label" for="defaultGroupExample3">For Personal Use</label>
              </div>

              <!-- Group of default radios - option 4 -->
              <div class="custom-control custom-radio mt-3 pl-5">
                  <input type="radio" name="purpose_type" class="custom-control-input purposetype" value="Others" id="defaultGroupExample4">
                  <label class="custom-control-label" for="defaultGroupExample4">Others</label>
              </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="reason" class="grey-text font-weight-light">Purpose Details</label>
                <textarea class="form-control" name="purpose" id="purpose"></textarea>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label class="grey-text font-weight-light">Item Type</label>
                <div class="custom-control custom-radio mt-2 pl-5">
                  <input type="radio" class="custom-control-input" id="itemTypeReturnable" name="item_type" value="Returnable" checked>
                  <label class="custom-control-label" for="itemTypeReturnable">Returnable</label>
                </div>
                <div class="custom-control custom-radio mt-3 pl-5">
                  <input type="radio" class="custom-control-input" id="itemTypeUnreturnable" name="item_type" value="Unreturnable">
                  <label class="custom-control-label" for="itemTypeUnreturnable">Unreturnable</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input checkbox1" id="defaultUnchecked">
                <label class="custom-control-label" for="defaultUnchecked">If not connected to FUMACO Inc.</label>
            </div>
              <div class="form-group">
                <label for="reported-through" class="grey-text font-weight-light">Company Name</label>
                  <input type="text" class="form-control textboxdisable" name="company_name" id="company_name">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="time-reported" class="grey-text font-weight-light">Address</label>
                  <textarea class="form-control textboxdisable" name="address" id="address"></textarea>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="received-by" class="grey-text font-weight-light">Contact No.</label>
                  <input type="text" class="form-control textboxdisable" name="tel_no" id="tel_no">
                </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="reason" class="grey-text font-weight-light">Item(s) -Required</label>
                <textarea class="form-control" name="item_description" id="item_description"></textarea>
              </div>
            </div>  
          </div>
        </div>
        
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="text-center">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmSubmission">
              <i class="fa fa-paper-plane-o mr-1"></i>SUBMIT
            </button>
           {{--  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmHome">
                  <i class="fa fa-home mr-2"></i>HOME
            </button> --}}
            </div>
        </div>
      </div>
     </form>
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="confirmSubmission" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">

    <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Confirm Submission</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5>Submit gatepass for approval?</h5>
          </div>
          <div class="modal-footer">
            <a href="#" onclick="submitme()" class="btn btn-primary">Yes</a>
            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
          </div>
      </div>
    </div>
</div>
<div class="modal fade" id="alertmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">

    <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Alert</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5>Please fill in the necessary fields!</h5>
          </div>
          <div class="modal-footer">
            <!-- <a href="/kiosk/home" class="btn btn-primary">Yes</a> -->
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
</div>
<div class="modal fade" id="confirmHome" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">

    <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5>Proceed to Homepage?</h5>
          </div>
          <div class="modal-footer">
            <a href="/kiosk/home" class="btn btn-primary redirect">Yes</a>
            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
          </div>
      </div>
    </div>
</div>
<div class="modal fade" id="confirmBack" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">

    <!-- Add .modal-dialog-centered to .modal-dialog to vertically center the modal -->
    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5>Cancel gatepass slip?</h5>
          </div>
          <div class="modal-footer">
            <a href="/kiosk/gatepass" class="btn btn-primary redirect">Yes</a>
            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
          </div>
      </div>
    </div>
</div>
 


@endsection
@section('script')
<script type="text/javascript" src="{{ asset('kiosk/datepicker/src/anypicker.js') }}"></script>

{{-- <script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.timepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/jquery.timepicker.css') }}" />
<script type="text/javascript" src="{{ asset('css/js/datepicker/datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/jquery.datepair.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" /> --}}



<script type="text/javascript">
  
  $("#datepairExample .date").AnyPicker({
      mode: "datetime",
      dateTimeFormat: "yyyy-MM-dd"
    });

  $("#datepairExample .time").AnyPicker({
      mode: "datetime",
      dateTimeFormat: "hh:mm AA"
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }

  });
    $(document).ready(function(){
      checkboxval();
      $('#returned_on').prop('readonly', true); 
      $('.time').prop('readonly', true); 

  })
        $('.checkbox1').on('change', function () {
        var checked = $(this).prop('checked');
        $('.textboxdisable').prop('disabled', !checked);
    });
</script>
<script type="text/javascript">
  function submitme(){
    var returned_on = document.forms["add-gatepass-form"]["returned_on"].value;
    var time = document.forms["add-gatepass-form"]["time"].value;
    var purpose = document.forms["add-gatepass-form"]["purpose"].value;
    var item_description = document.forms["add-gatepass-form"]["item_description"].value;


      if (returned_on == "") {
        $('#alertmodal').modal('show');
        $('#confirmSubmission').modal('hide');
        return false;
      }
      if (time == "") {
        $('#alertmodal').modal('show');
        $('#confirmSubmission').modal('hide');
        return false;
      }
      if (purpose == "") {
        $('#alertmodal').modal('show');
        $('#confirmSubmission').modal('hide');
        return false;
      }
      if (item_description == "") {
        $('#alertmodal').modal('show');
        $('#confirmSubmission').modal('hide');
        return false;
      }
      

     document.getElementById('add-gatepass-form').submit();
  }
</script>
<script type="text/javascript">
  function refresh(){
    document.getElementById("add-gatepass-form").reset();
  }
  function cancel_button(){
    $('#confirmBack').modal('show');
  }
  function checkboxval() {
  // Get the checkbox
  var checkBox = document.getElementById("defaultUnchecked");
  // Get the output text
  var text = document.getElementById("text");

  // If the checkbox is checked, display the output text
  var checked = $('.checkbox1').prop('checked');
  if (checkBox.checked == true){
  $('.textboxdisable').prop('disabled', !checked);
  } else {
    $('.textboxdisable').prop('disabled', !checked);
  }
}
</script>
@endsection