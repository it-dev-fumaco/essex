@extends('portal.app')

@section('content')
@include('portal.modals.add_policy_modal')
@include('portal.modals.edit_policy_modal')
@include('portal.modals.delete_policy_modal')
<div class="main-container">
   <div class="section">
      <div class="container">
         <h1 class="title-2 center" style="margin-top: -40px; border: 0;">Search Results</h1>
          <div class="card">
            <div class="card-header" align="center"><b>{{ count($searchResults) }} results found for "{{ request('query') }}"</b>
            </div>
            <br>
              <div class="card-body">
                @foreach(collect($searchResults)->groupBy('type') as $type => $modelSearchResults)
                  @foreach($modelSearchResults as $searchResult)
                    <ul>
                       <li>
                        <p class="uppercase"><i class="icon-arrow-right"></i><b><i><a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a></i></b>{{ $searchResult->category }}<br></p>
                        {{ $searchResult->phone }}{!! str_limit($searchResult->description , $limit = 150, $end = '...') !!}
                        <br>
                      </li>
                      <br>
                    </ul>
                  @endforeach
                @endforeach
              </div>
            </div>
      </div>
   </div>
</div>

<style type="text/css">
.uppercase { text-transform: uppercase; }
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
