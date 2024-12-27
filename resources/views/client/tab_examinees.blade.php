@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
@include('client.modals.tab_add_examinee')
<div class="col-md-12">
   <h2 class="section-title center">Online Examination System</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="online-exam-tab">
   @include('client.tab_header_admin', ['current_page' => '/tabExaminees'])
   <div class="tab-content">
      <div class="tab-pane in active" id="tab-examinee-list">
         <div class="row">
            <div class="inner-box featured">
               <div class="pull-right" style="font-size: 23pt; padding-right: 10px;">
                  <a href="/tabExaminees"><i class="fa fa-refresh" aria-hidden="true"></i></a>
               </div>
               <h2 class="title-2">Examinee(s)</h2>
               <div class="row">
                  <div class="col-md-12">
                     <div id="successMes"></div>
                  </div>
                  <div class="col-md-12">
                    <h2 class="section-title center" style="font-size: 14pt;">On Going Examination</h2>
                     <div id="examinee-tbl"></div>
                     <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addExaminee" style="float: left; z-index: 1;">
                        <i class="fa fa-plus"></i> Examinee
                     </a>
                     <table class="table" id="example" style="font-size: 10pt;">
                        <thead>
                           <tr>
                              <th style="text-align: center;">No.</th>
                              <th>Examinee</th>
                              <th style="text-align: center;">User Type</th>
                              <th style="text-align: center;">Exam Code</th>
                              <th>Exam Title</th>
                              <th style="text-align: center;">Exam Date</th>
                              <th style="text-align: center;">Start - End</th>
                              <th style="text-align: center;">Duration</th>
                              <th style="text-align: center;">Valid Until</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody class="table-body">
                           @foreach($examinees as $index => $examinee)
                           <span hidden>{{ $from = \Carbon\Carbon::createFromFormat('H:i', date('h:i',strtotime($examinee->start_time))) }}</span>
                           <span hidden>{{ $to = \Carbon\Carbon::createFromFormat('H:i', date('h:i',strtotime($examinee->end_time))) }}</span>
                           @php 
                              $row_class = '';
                              if ($examinee->status == 'On Going') {
                                 $status_color = '#7DCEA0';
                                 $row_class = 'blink';
                              }elseif ($examinee->status == 'Not Started') {
                                 $status_color = '#EC7063';
                              }else{
                                 $status_color = '';
                              }
                           @endphp
                           <tr style="background-color: {{ $status_color }}" class="{{$row_class}}">
                              <td style="text-align: center;">{{ $index + 1 }}</td>
                              <td>{{ $examinee->employee_name }}</td>
                              <td style="text-align: center;">{{ $examinee->user_type }}</td>
                              <td style="text-align: center;">{{ $examinee->exam_code }}</td>
                              <td>{{ $examinee->exam_title }}</td>
                              <td style="text-align: center;">{{ date('m-d-Y', strtotime($examinee->date_of_exam)) }}</td>
                              @if($examinee->status == 'Completed')
                                 <td style="text-align: center;">{{ date('h:i A',strtotime($examinee->start_time)) }} - {{ date('h:i A',strtotime($examinee->end_time)) }}</td>
                              @else
                                 <td style="text-align: center;">{{ $examinee->status }}</td>
                              @endif
                              <td style="text-align: center;">
                              @if($examinee->status == 'Completed')
                                 {{ $diff_in_hours = $to->diffInMinutes($from) }} min(s)
                              @else
                                 –
                              @endif
                              </td>
                              @if(date('m-d-Y') <= date('m-d-Y',strtotime($examinee->validity_date)))
                              <td style="text-align: center;">{{ date('m-d-Y', strtotime($examinee->validity_date)) }}</td>
                              @else
                              @if($examinee->start_time)
                              <td style="text-align: center;">{{ date('m-d-Y', strtotime($examinee->validity_date)) }}</td>
                              @else
                              <td style="text-align: center;">Validity Expired</td>
                              @endif
                              @endif
                              <td>
                                 <a href="#" data-toggle="modal" class="hover-icon" data-target="#deleteExaminee{{$examinee->examinee_id}}" style="font-size: 15pt; color: #C0392B;">
                                    <i class="fa fa-trash"></i>
                                 </a>
                              </td>
                           </tr>
                             @include('client.modals.tab_delete_examinee')
                             {{-- @include('client.modals.tab_edit_examinee') --}}
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="cancelExamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Cancel Exam</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <center>
                  Note: This will reset exam timer and logs
               </center>
               <span id="exam-id" hidden></span>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary" id="confirm-cancel-btn">Cancel</button>
            </div>
         </div>
      </div>
 </div>

<style>
#online-exam-tab .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}
 input, select{
   height: 35px;
   width: 100%;
}
@-webkit-keyframes blinker {
  from { background-color: #7DCEA0; }
  to { background-color: inherit; }
}
@-moz-keyframes blinker {
  from { background-color: #7DCEA0; }
  to { background-color: inherit; }
}
@-o-keyframes blinker {
  from { background-color: #7DCEA0; }
  to { background-color: inherit; }
}
@keyframes blinker {
  from { background-color: #7DCEA0; }
  to { background-color: inherit; }
}

.blink{
   text-decoration: blink;
   -webkit-animation-name: blinker;
   -webkit-animation-duration: 0.6s;
   -webkit-animation-iteration-count:infinite;
   -webkit-animation-timing-function:ease-in-out;
   -webkit-animation-direction: alternate;
}
</style>


@endsection

@section('script')

<script type="text/javascript">
   $(document).ready(function(){
      $('#example').DataTable({
         "bLengthChange": false,
         "ordering": false,
         "dom": '<"top"f>rt<"bottom"ip><"clear">'
      });

      $(document).on('click', '.cancel-exam-btn', function (){
         $('#exam-id').text($(this).data('examinee-id'));
         $('#cancelExamModal').modal('show');
      });

      $(document).on('click', '#confirm-cancel-btn', function (){
         var exam_id = $('#exam-id').text();
         $.ajax({
            type: 'GET',
            url:  '/cancel_ongoing_exam/' + exam_id,
            success: function(data){
               $('#cancelExamModal').modal('hide');
               $.bootstrapGrowl("<center><i class=\"fa fa-check-square-o\" style=\"font-size: 30pt; float: left; padding-right: 10px;\"></i><span style=\"display:block; font-size: 12pt; padding-top: 5px;\">" + data.message + "</span></center>", {
                  type: data.success ? 'success' : 'danger',
                  align: 'center',
                  delay: 4000,
                  width: 450,
                  offset: {from: 'top', amount: 300},
                  stackup_spacing: 20
               });
               loadExaminee();
            }
         });
      })

      setInterval(loadExaminee, 5000);

      loadExaminee();
      function loadExaminee(){
        $.ajax({
            type: 'GET',
            url:  '/tabExaminees',
            success: function(data){
              $('#examinee-tbl').html(data);
            }
         });
      }
      
      function loadUsers(){
        var optionSelected = $("option:selected", '#addExaminee #department_id').val();
         $.ajax({
            type: 'GET',
            url:  '/admin/get_users/' + optionSelected,
            success: function(data){
               $("#addExaminee #user_id").empty();
               for(var i=0; i < data.users.length; i++){
                  var o = new Option(data.users[i].employee_name, data.users[i].id);
                  $(o).html(data.users[i].employee_name);
                  $("#addExaminee #user_id").append(o);
               }
               $("#addExaminee #exam_id").empty();
               for(var i=0; i < data.exams.length; i++){
                  var o = new Option(data.exams[i].exam_title, data.exams[i].duration_in_minutes + ',' + data.exams[i].exam_id);
                  $(o).html(data.exams[i].exam_title);
                  $("#addExaminee #exam_id").append(o);
               }
            }
         });
      }

     $('#addExaminee #department_id').change(function(){
         loadUsers();
      });

    $('#addExaminee').on('show.bs.modal',function(){
      loadUsers();
    });

    $(document).on('submit', '#formAddExaminee', function(event){
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "/tabAddExaminee",
            data: $(this).serialize(),
            success: function(data){
               if(data.error){
                  $('#errorDiv').html("<div class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <center>" + data.error + "</center></div>");
               }else{
                  $('#successMes').html("<div class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert'>&times;</button> <center>" + data.message + "</center></div>");
                  $('#addExaminee').modal('hide'); 
                  location.reload();
               }
            }
        });
    });

    $('.modal').on('hidden.bs.modal', function(){
      $(this).find('form')[0].reset();
    });
});
</script>
@endsection

