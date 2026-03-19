@extends('client.app')
@section('content')
@include('client.modules.gatepass.employee_accountability.modals.addIssuedItem')
<div class="col-md-12" style="margin-top: -30px;">
  <h2 class="section-title center">Employee Item Accountability</h2>
  <a href="/client/gatepass/employee_accountability">
    <i class="fa fa-arrow-circle-o-left" style="font-size: 40pt; padding: 5px 80px; margin-top: -70px; float: left;"></i>
  </a>
    <a style="font-size: 40pt; padding: 10px 90px; margin-top: -70px; float: right;" onclick="window.open('/printItem/{{ $employee_profile->user_id }}', '_blank', 'location=yes,height=570,width=520,scrollbars=no,status=yes');">
                         <i class="fa fa-print" style="font-size: 30px;"></i></div>
                        </a>
</div>
         <div class="col-md-12">
                  
          
                   <table style="width: 100%; font-size: 12pt;padding-top: -10px;" border="0">
                      
                      <tr>
                        
                        <td style="padding-left: 90px; width: 10%;">Employee name:</td>
                        <td style="padding: 1px 10px; width: 20%;">{{ $employee_profile->employee_name }}</td>
                        <td style="padding-left: 90px; width: 10%;">Department:</td>
                        <td style="padding: 1px 10px; width: 20%;">{{ $depart }}</td>
                      </tr>
                      <br>
                      <tr>
                        <td style="padding-left: 90px; width: 20%;">Designation:</td>
                        <td style="padding: 1px 10px; width: 20%;">{{ $desig }}</td>
                        <td style="padding-left: 90px; width: 20%;">Employment Status:</td>
                        <td style="padding: 1px 10px; width: 20%;">{{ $status }}</td>
                      </tr>
                      <tr>
                        <td style="padding-left: 90px; width: 20%;"></td>
                        <td style="padding: 1px 10px; width: 20%;"></td>
                     
                        <td style="padding-left: 90px; width: 30%;">No. of Unreturned Gatepass:</td>
                        @forelse($gatepass as $row)
                           <td style="padding: 1px 10px; width: 20%;">{{ $row->total_issued_items }}</td>
                          @empty
                           <td style="padding: 1px 10px; width: 20%;">0</td>
                          @endforelse
                      </tr>
                      
                  
                      </table>
         </div>
   <div class="tab-content" style="padding-top: 11%;">
      <div class="tab-pane in active" id="tab-applicants-list">
         <div class="row">
          <div class="col-sm-12 col-md-10 col-md-offset-1">
            <div class="inner-box featured">
               <h2 class="title-2" align="center">Issued Item(s) to Employee</h2>
               <div class="row">
                  <div class="col-md-12">
                     @if(session("message"))
                     <div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                        <center>{!! session("message") !!}</center>
                     </div>
                     @endif
                  </div>
                  <div class="col-md-12" id="div1" name="div1">
                     <table class="table" id="example" style="font-size: 11pt;">
                      <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addAsset" style="float: left; z-index: 1;">
                            <i class="fa fa-plus"></i> Add
                          </a>
                        <thead>
                           <tr>
                              <th>ID</th>
                              <th></th>
                              <th>Item Code</th>
                              <th>Classification</th>
                              <th>Details</th>
                              <th>Date Issued</th>
                              <th>Issued By</th>
                              <th>Status</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody class="table-body">
                          @foreach($issued_to as $row)
                           <tr>
                           <td>{{ $row->id }}</td>
                           <td width="20%">
                            @if($row->filepath)
                            <img src="{{ Illuminate\Support\Facades\Storage::disk('upcloud')->url(ltrim((string) $row->filepath, '/')) }}" alt="" style="border: 1px solid #ddd;border-radius: 4px;padding: 5px;width: 200px;">
                             @else
                            <img src="{{ Illuminate\Support\Facades\Storage::disk('upcloud')->url('uploads/assetpicture/thumbnail/notfound.png') }}" alt=""style="border: 1px solid #ddd;border-radius: 4px;padding: 5px;width: 200px;">
                      @endif
                           </td>
                           <td  width="10%">{{ $row->item_code }}</td>
                           <td  width="10%">{{ $row->class }}</td>

                           <td width="15%">
                            <b>{{ $row->name }}</b><br>
                            {{ $row->desc }}<br>
                            <!-- <b>Category :</b>{{ $row->category }}<br> -->
                            
                            <!-- <b>Purchase Date :</b>{{ $row->purchase_date }}<br>
                            <b>Purchase Order No :</b>{{ $row->purchase_orderno }}<br>
                            <b>Item Status :</b>{{ $row->item_status }}<br> -->
                            
                           </td>
                           <td width="13%">{{ $row->issued_date }}</td>
                           <td width="10%">{{ $row->issued_by_name }}</td>
                           <td>{{ $row->status }}</td>
                           <td width="12%">
                            <input type="hidden" name="idno" id="idno" value="{{ $row->id }}">
                              <a href="#" class="hover-icon" data-toggle="modal" data-target="#edit-itemlist-{{ $row->id }}">
                                 <i class="fa fa-pencil" style="font-size: 15pt; color: #27AE60;"></i>
                              </a>
                              <a href="#" class="hover-icon"  data-toggle="modal" data-target="#view-itemlist-{{ $row->id }}">
                                 <i class="fa fa-search" style="font-size: 15pt; color: #FFA500;"></i>
                              </a>
                              <a href="#" class="hover-icon"  data-toggle="modal" data-target="#delete-itemlist-{{ $row->id }}">
                                 <i class="fa fa-trash" style="font-size: 15pt; color: #C0392B;"></i> 
                              </a>
                           </td>
                           @include('client.item_accountability.modals.editItem')
                           @include('client.item_accountability.modals.deleteItem')
                           @include('client.item_accountability.modals.viewItem')
                           @endforeach 
                        </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
 </div>
</div>
@endsection

@section('script')

<!-- <script type="text/javascript" src="{{ asset('css/js/jquery.min.js') }}"></script> -->
<!-- <script type="text/javascript" src="{{ asset('css/js/bootstrap.min.js') }}"></script> -->
<script type="text/javascript" src="{{ asset('css/js/standalone/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('css/js/standalone/select2.full.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/standalone/select2.min.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/standalone/select2.css') }}" />
<script type="text/javascript">
  function printElem(div1) {
        //Get the HTML of div
        var divElements = document.getElementById(div1).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;
        //Reset the page's HTML with div's HTML only
        document.body.innerHTML = 
          "<html><head><title></title></head><body>" + 
          divElements + "</body>";
        //Print Page
        window.print();
        //Restore orignal HTML
        document.body.innerHTML = oldPage;

    }
</script>

<!-- <script>
$(document).ready(function() {
$('#example').DataTable({
      "bLengthChange": false,
      "ordering": false,
      "filter": false,
      "dom": '<"top"f>rt<"bottom"ip><"clear">',

   });
});

</script> -->

<script>

   $("#itemcode").select2({
       dropdownParent: $("#addAsset")

});
   $(document).ready(function(){
    console.log("ready");
    // $(".editview").show();
    // $(".imgup").hide();
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
            $('#purchase_date').val(data.purchase_date);
            $('#desc').val(data.description);
            $('#purchase_order').val(data.purchase_order);
            // $('#qty').val(data.quantity);
            $('#status').val(data.status);
            // $("#serial").hide();
            // $("#mc").hide();
           
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

uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
            }
        }
      
    });
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

uploadFile.closest(".imgUp").find('.imagePrevieww').css("background-image", "url("+this.result+")");
            }
        }
      
    });
});
</script>
<!-- <script type="text/javascript">
     $(document).ready(function(){
      $('.img').click(function(e){
         e.preventDefault();
         $(".hidehide").hide();
         $(".imagePreview").show();
         
         

        
      });
   });


</script> -->
@endsection


<style>
  .hideme{
    display: none;
  }
#online-exam-tab .nav-tabs > li {
   float: none;
   display: inline-block;
   /*zoom: 1;*/
}
</style>
<style type="text/css">
  .lineme{
   outline: none;
}
.imagePrevieww {
    width: 80%;
    height: 100px;
    background-position: center center;
  background-color:#fff;
    background-size: cover;
  background-repeat:no-repeat;
    display: inline-block;
  box-shadow:0px -3px 6px 2px rgba(0,0,0,0.2);
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

