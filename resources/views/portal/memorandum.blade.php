@extends('portal.app')

@section('content')
@include('portal.modals.add_policy_modal')
@include('portal.modals.edit_policy_modal')
@include('portal.modals.delete_policy_modal')
<div class="main-container">
  <div class="section">
    <div class="col-12 col-xl-10 mx-auto">
      <h1 class="title-2 center" style="margin-top: -40px; border: 0;">Memorandum / Policy</h1>
      @if(Auth::user() && in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
      <div class="row">
        <div class="col-md-12">
          <div class="pull-right">
            <div class="form-group">
              <button type="button" class="btn btn-success" id="addPolicyBtn">
                <i class="fa fa-plus"></i><span>Add Policy</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      @endif
      <div class="row">
        <div class="container-fluid">
          @if(count($policiesAllDept) > 0)
          <h3 class="title-2" style="border-bottom: 1px solid #E8E8E8; padding:0; margin: 10px 0;">All Department(s)
          </h3>
          <div class="row">
            @foreach($policiesAllDept as $policy)
            <div class="col-xl-3 col-md-4 col-sm-6 col-xs-12">
              <div class="support-inner">
                <div class="support-info" style="margin: 5px; padding: 20px 3px 20px 80px;">
                  <div class="info-title">
                    <a href="{{ asset('storage/uploads/files/'.$policy->file_attachment) }}" class="text-decoration-none one-line ellipsis responsive-font" target="_blank">
                      <i class="fas fa-file" style="font-size: 32pt;"></i>{{ $policy->subject }}
                    </a>
                    <span>{!! str_limit($policy->description, 30) ? '' : '<br>' !!}</span>
                  </div>
                  @if(Auth::user() && in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
                    <div class="container-fluid p-0">
                      <a href="#" class="text-decoration-none" id="editPolicyBtn" data-id="{{ $policy->policy_id }}"
                        data-dept="{{ $policy->department_id }}" data-subject="{{ $policy->subject }}"
                        data-desc="{{ $policy->description }}" data-file="{{ $policy->file_attachment }}"><i
                          class="fa fa-pencil"></i> Edit</a> |
                      <a href="#" class="text-decoration-none" id="deletePolicyBtn" data-id="{{ $policy->policy_id }}"
                        data-subject="{{ $policy->subject }}"><i class="fa fa-trash"></i> Delete</a>
                    </div>
                  @endif
                </div>
              </div>
            </div>
            @endforeach
          </div>

          @endif

          @foreach($policiesByDept as $row)
          <h3 class="title-2" style="border-bottom: 1px solid #E8E8E8; padding:0; margin: 10px 0;">{{ $row['department']
            }}</h3>
          <div class="row">
            <div class="support-inner">
              @foreach(collect($row['policies']) as $policy)
              <div class="col-md-4 col-sm-6 col-xs-12">
                @if(Auth::user() && in_array(Auth::user()->user_group, ['Editor', 'HR Personnel']))
                <div style="text-align: right;">
                  <a href="#" id="editPolicyBtn" data-id="{{ $policy->policy_id }}"
                    data-dept="{{ $policy->department_id }}" data-subject="{{ $policy->subject }}"
                    data-desc="{{ $policy->description }}" data-file="{{ $policy->file_attachment }}"><i
                      class="fa fa-pencil"></i> Edit</a> |
                  <a href="#" id="deletePolicyBtn" data-id="{{ $policy->policy_id }}"
                    data-subject="{{ $policy->subject }}"><i class="fa fa-trash"></i> Delete</a>
                </div>
                @endif
                <div class="support-info" style="margin: 5px; padding: 20px 3px 20px 80px;">
                  <div class="info-title">
                    <a href="{{ asset('storage/uploads/files/'.$policy->file_attachment) }}" target="_blank"><i
                        class="fa fa-file-text-o" style="font-size: 32pt;"></i>{{ str_limit($policy->subject, 27) }}</a>
                    <span>{!! str_limit($policy->description, 30) ? '' : '<br>' !!}</span>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>


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