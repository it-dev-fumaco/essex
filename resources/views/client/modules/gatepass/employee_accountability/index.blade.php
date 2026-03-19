@extends('client.app')
@section('content')
@include('client.modules.nav_menu')
<div class="col-md-12">
   <h2 class="section-title center">Gatepass</h2>
   <a href="/home">
      <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px; margin-bottom: -50px; float: left;"></i>
   </a>
</div>

<div id="tabs">
   <ul class="nav nav-tabs" style="text-align: center;">
      {{-- <li><a href="/module/gatepass/analytics">Analytics</a></li> --}}
      <li><a href="/client/gatepass/history">Gatepass History</a></li>
      <li><a href="/client/gatepass/unreturned_gatepass">Unreturned Gatepass Item(s)</a></li>
      <li class="active"><a href="/client/gatepass/employee_accountability">Employee Accountabilities</a></li>
      <li><a href="/client/gatepass/company_asset">Fixed Asset(s)</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane in active">
         <div class="row">
            <div class="col-sm-12 col-md-8 col-md-offset-2">
            <div class="inner-box featured">
               <h2 class="title-2">Employee Accountabilities</h2>
               <div class="row">
                  <div class="col-md-12">
                     @if(session("message"))
                     <div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <center>{!! session("message") !!}</center>
                     </div>
                     @endif
                     <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addAsset" style="float: left; z-index: 1;">
                        <i class="fa fa-plus"></i> Add
                     </a>
                        <table class="table" id="examples">
                           <thead>
                              <tr>
                                 <th>Employee Name</th>
                                 <th>No. of Issued Item(s)</th>
                                 <th>Actions</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($employee_accountability as $row)
                              <tr>
                                 <td>{{ $row->employee_name }}</td>
                                 <td>{{ $row->total_issued_items }} item(s)</td>

                                 <td>
                                    <a href="/itemAccountability/{{ $row->issued_to }}" >
                                       <i class="fa fa-search icon-view"></i>
                                    </a>
                                 
                                    </a>
                                 </td>
                              </tr>
                              @endforeach
                           </tbody>
                        </table>
                        <div id="item_div">
                     
                  </div>
                  </div>
               </div>
            </div>
            </div>
         </div>
      </div>
   </div>
</div>

@include('client.modules.gatepass.employee_accountability.modals.edit')
@include('client.modules.gatepass.employee_accountability.modals.update')
@include('client.modules.gatepass.employee_accountability.modals.addIssuedItem')

@endsection
@section('script')
<!-- <script type="text/javascript" src="{{ asset('css/js/jquery.js') }}"></script> -->
<!-- <script type="text/javascript" src="{{ asset('css/js/jquery-2.0.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/jquery.dataTables.min.js') }}"></script> -->
<!-- <script type="text/javascript" src="{{ asset('css/js/bootstrap.min.js') }}"></script> -->
<script type="text/javascript" src="{{ asset('css/js/standalone/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/standalone/select2.full.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/standalone/select2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/standalone/select2.css') }}" />
<script>
$(document).ready(function() {
$('#examples').DataTable({
      "bLengthChange": false,
      "ordering": false,
      "dom": '<"top"f>rt<"bottom"ip><"clear">',

   });
});

</script>
<script>

   $(document).ready(function(){
      $('.clearme').val('');
      if($('#item_category').val()== 'Car'){
                    $(".serialu").hide();
                    $(".mcu").hide();
                    $(".plateu").show();
                    $(".modelu").show();
                    $(".brandu").show();
               }else{
                    // $("#seriald").show();
                    // $("#mcd").hide();
                    $(".plateu").hide();                   
               }
      $('.edit-emp-accountability').click(function(e){
         e.preventDefault();
         var user_id = $(this).data('id');

         $("#edit-emp-accountability-modal table tbody").empty();

         $.ajax({
            url: "/getItemsIssuedtoEmployee/"+user_id,
            method: "GET",
            success: function(data) {
                
               $.each(data, function(i, d){
                  var row = '<tr>' +
                           
                           '<td>' + d.item_code + '</td>' +
                           '<td>' + d.desc + '</td>' +
                           '<td>' + d.serial_no + '</td>' +
                           '<td>' + d.mcaddress + '</td>' +
                           '<td>' + d.issued_date + '</td>' +
                           '<td>' + d.status + '</td>' +
                        
                   '</tr>';

                   $("#edit-emp-accountability-modal table tbody").append(row);  
               });

               $('#edit-emp-accountability-modal').modal('show');
            },
            error: function(data) {
               alert('Error fetching data!');
            }
         });
      });
      $('.update-emp-accountability').click(function(e){
         e.preventDefault();
         var user_id = $(this).data('id');

         $.ajax({
            url: "/getItemsIssuedtoEmployee/"+user_id,
            method: "GET",
            success: function(data) {
               $.each(data, function(i, d){
               $('#updateitemcode').val(d.item_code);
               $('#updatecategory').val(d.category);
               $('#updatename').val(d.name);
               $('#updatedescc').val(d.desc);
               $('#updateitem_category').val(d.class);
               $('#updatepurchase_date').val(d.purchase_date);
               $('#updatepurchase_order').val(d.purchase_orderno);
               $('#updateqty').val(d.quantity);
               $('#updatestatus').val(d.item_status);
               // $('#updateqty').val(d.qty);
               $('#updatebrand').val(d.brand);
               $('#updatemodel').val(d.model);
               $('#updateserial_no').val(d.serial_no);
               $('#updateplate_no').val(d.plate_no);
               $('#updatemcaddress').val(d.mcaddress);
                
               
 
               });
               $('#updatemodal').modal('show');

            },
            error: function(data) {
               alert('Error fetching data!');
            }
         });
      });
   });
      function function_imei(){
        var itemcode = document.getElementById('itemcode').value;
        var category = document.getElementById('category').value;

        data = {
         itemcode : itemcode,
         category : category,
        }

        $.ajax({
            url: '/getInfo/',
            type: 'get',
            dataType: 'JSON',
            data: data,
            success: function(data) {
            $('#name').val(data.name);
            $('#item_category').val(data.asset_category);
            // $('#item_category').val(data.cat_name);
            $('#item_classitem').val(data.com_type);
            $('#purchase_date').val(data.purchase_date);
            $('#desc').val(data.description);
            $('#purchase_order').val(data.purchase_order);
            // $('#qty').val(data.quantity);
            $('#status').val(data.status);
            if($('#item_category').val()== 'Car'){
                    $(".serialu").hide();
                    $(".mcu").hide();
                    $(".plateu").show();
                    $(".modelu").show();
                    $(".brandu").show();
               }else{
                    // $("#seriald").show();
                    // $("#mcd").hide();
                    $(".serialu").show();
                    $(".mcu").show();
                    $(".modelu").show();
                    $(".brandu").show();
                    $(".plateu").hide();                   
               }           
            },
            error: function(data) {
               alert('Error fetching data!');
            }
        });
}


      function function_one(){
        var category = document.getElementById('category').value;
        data = {
         category : category,
        }

        $.ajax({
            url: '/getCateg/',
            type: 'get',
            dataType: 'JSON',
            data: data,
            success: function(result) {
            $('#itemcode').html(result);
           
            },
            error: function(result) {
               alert('Error fetching data!');
            }
        });
}
 $('#category').change(function(){
  $('.clearme').val('');

 });

   $('#submit').on('submit', function(e) {
       e.preventDefault(); 
       
           var name = $('#name').val();
           var category = $('#category').val();
           var item_category =  $('#item_category').val();
           var purchase_date = $('#purchase_date').val();
           var desc = $('#desc').val();
           var purchase_order= $('#purchase_order').val();
           var qty= $('#qty').val();
           var status= $('#status').val();
           var item_code= $('#itemcode').val();
         data=
           { name:name,
            category:category,
            item_code:item_code,
            item_category:item_category,
            purchase_date:purchase_date,
            desc:desc,
            purchase_order:purchase_order,
            qty:qty,
            status:status
           }


       $.ajax({
           type: "POST",
           url: '/addAsset/',
           data: data,
           dataType: 'JSON',
          

           success: function(data) {
               alert(data);
           },
           error: function(data) {
               alert('Error fetching data!');
            }
       });
   });



</script>
<script>
   $("#itemcode").select2({
       dropdownParent: $("#addAsset")

});
</script>
<script type="text/javascript">
      $('.js-example-basic-single').click(function(){
               if($('#item_category').val()== 'Car'){
                    $(".serialu").hide();
                    $(".mcu").hide();
                    $(".plateu").show();
                    $(".modelu").show();
                    $(".brandu").show();
               }else{
                    // $("#seriald").show();
                    // $("#mcd").hide();
                    $(".plateu").hide();                   
               }
      });


</script>
<script type="text/javascript">
$(".imgAdd").click(function(){
  $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
});
$(document).on("click", "i.del" , function() {
  $(this).parent().remove();
});
$(function() {
    $(document).on("change",".uploadFile", function()
    {
        var uploadFile = $(this);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
 
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
 
            reader.onloadend = function(){ // set image data as background of div
                //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
            }
        }
      
    });
});
</script>




@endsection

<style>
#tabs .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}
</style>
<style type="text/css">
.lineme{
   outline: none;
}
.imagePreview {
    width: 80%;
    height: 100px;
    background-position: center center;
  background:url({{ Illuminate\Support\Facades\Storage::disk('upcloud')->url('uploads/assetpicture/thumbnail/notfound.png') }});
  background-color:#fff;
    background-size: cover;
  background-repeat:no-repeat;
    display: inline-block;
  box-shadow:0px -3px 6px 2px rgba(0,0,0,0.2);
}
.btn-primary
{
  display:block;
  border-radius:0px;
  box-shadow:0px 4px 6px 2px rgba(0,0,0,0.2);
  margin-top:-5px;
}
.imgUp
{
  margin-bottom:15px;
}
.del
{
  position:absolute;
  top:0px;
  right:15px;
  width:30px;
  height:30px;
  text-align:center;
  line-height:30px;
  background-color:rgba(255,255,255,0.6);
  cursor:pointer;
}
.imgAdd
{
  width:30px;
  height:30px;
  border-radius:50%;
  background-color:#4bd7ef;
  color:#fff;
  box-shadow:0px 0px 2px 1px rgba(0,0,0,0.2);
  text-align:center;
  line-height:30px;
  margin-top:0px;
  cursor:pointer;
  font-size:15px;
}
</style>
