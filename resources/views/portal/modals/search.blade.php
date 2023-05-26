@extends('portal.app')

@section('content')
@include('portal.modals.add_policy_modal')
@include('portal.modals.edit_policy_modal')
@include('portal.modals.delete_policy_modal')
@php
    $chunk_count = count($searchResults) / 2;
    $chunk_count = round($chunk_count) < 1 ? count($searchResults) : round($chunk_count);
    $chunk = $searchResults->chunk($chunk_count);
@endphp
<div class="main-container">
   <div class="section">
    <div class="container-fluid">
      <div class="col-md-10 col-md-offset-1">
         <div class="card">
           <div class="card-header" align="center">
            <h3>{{ count($searchResults) }} results found for "{{ request('query') }}"</h3>
           </div>
           <br>
             <div class="card-body">
              <div class="row">
                @foreach ($chunk as $searchResults)
                <div class="col-md-6" style="padding: 10px;">
                  @foreach($searchResults as $searchResult)
                    @php
                      if(is_object($searchResult)){
                        $url = $searchResult->url;
                        $title = $searchResult->title;
                        $category = $searchResult->category;
                        $description = $searchResult->description;
                        $icon = 'icon-info';
                      }else{
                        $url = $searchResult['url'];
                        $title = $searchResult['title'];
                        $category = $searchResult['category'];
                        $description = $searchResult['description'];
                        $icon = 'fa fa-file';
                      }
                    @endphp
                      <div class="row" style="margin: 20px 0 20px 0;">
                        <div class="col-md-1 text-center">
                          <i class="{{ $icon }}" style="font-size: 25pt;"></i>
                        </div>
                        <div class="col-md-11" style="padding: 0;">
                          <a href="{{ $url }}" class="url text-primary"><h5>{{ $title }}</h5></a>
                          <span class="text-muted">{{ $category }}</span>
                          <p>{{ str_limit(strip_tags($description), $limit = 100, $end = '...') }}</p>
                        </div>
                      </div>
                      <hr style="border: 1px solid rgba(0,0,0,.1)">
                    @endforeach
                  </div>
                @endforeach
              </div>
             </div>
           </div>
     </div>
    </div>
      
   </div>
</div>

<style type="text/css">
.uppercase { text-transform: uppercase; }
.url:hover{
  transition: .4s;
  text-decoration: underline
}
</style>

@endsection

@section('script')

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
<script>
    $(document).on('click', '#addPolicyBtn', function(event){
        event.preventDefault();
        $('#addPolicyModal').modal('show');
    });

    $(document).on('click', '#editPolicyBtn', function(event){
        event.preventDefault();
        $('#editPolicyModal .policy_id').val($(this).data('id'));
        $('#editPolicyModal .subject').val($(this).data('subject'));
        $('#editPolicyModal .department').val($(this).data('dept'));
        $('#editPolicyModal .old_file').val($(this).data('file'));
        CKEDITOR.instances['description'].setData($(this).data('desc'));
        $('#editPolicyModal').modal('show');
    });

    $(document).on('click', '#deletePolicyBtn', function(event){
        event.preventDefault();
        $('#deletePolicyModal .policy_id').val($(this).data('id'));
        $('#deletePolicyModal .subject').text($(this).data('subject'));
        $('#deletePolicyModal').modal('show');
    });

    // CKEDITOR.config.height = 450;
</script>
@endsection
