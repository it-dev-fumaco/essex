@extends('portal.app')

@section('content')
@include('portal.modals.add_policy_modal')
@include('portal.modals.edit_policy_modal')
@include('portal.modals.delete_policy_modal')
  {{-- @php
    $chunk_count = count($searchResults) / 2;
    $chunk_count = round($chunk_count) < 1 ? count($searchResults) : round($chunk_count); $chunk=$searchResults->
    chunk($chunk_count);
  @endphp --}}
  <div class="main-container">
    <div class="section" style="padding: 0 !important; min-height: 80vh">
      <div id="imagecontainer" class="container-fluid">
        <div class="container-fluid">
          <div class="col-8 col-xl-6 mx-auto" style="padding-top: 30px;">
            <form action="{{ route('search') }}" id="searh-form" method="get">
              <div class="row" style="padding: 0; margin:0 ">
                <div class="col-md-12 text-center">
                  <h3 style="color: #fff;">{{ count($searchResults) }} results found for "{{ request('query') }}"</h3>
                </div>
                <div class="col-md-10" style="padding: 0; margin: 0">
                  <input type="text" class="form-control carousel-search" type="search" name="query" placeholder="How can we help you today?" autocomplete="off" value="{{ request('query') }}">
                </div>
                <div class="col-md-2" style="padding: 0; margin: 0">
                  <button type="submit" class="btn bg-success h-100 w-100" style="border-radius: 0 25px 25px 0; font-weight: 700">Search</button>
                </div>
              </div>
            </form>
            <div id="autocomplete-container" class="container p-0 w-50 bg-white border-secondary d-none"></div>
          </div>
        </div>
      </div>
      <div class="container-fluid">
        <div class="col-12 col-xl-10 mx-auto">
          <div class="card">
            <div class="card-body">
              <div class="row">
                @foreach($searchResults as $searchResult)
                  @php
                    if(is_object($searchResult)){
                      $url = $searchResult->url;
                      $title = $searchResult->title;
                      $category = $searchResult->category;
                      $description = $searchResult->description;
                      $phone = $searchResult->phone;
                      $type = $searchResult->type;
                    }else{
                      $url = $searchResult['url'];
                      $title = $searchResult['title'];
                      $category = $searchResult['category'];
                      $description = $searchResult['description'];
                      $phone = $searchResult['phone'];
                      $type = $searchResult['type'];
                    }

                    switch ($type) {
                      case 'users':
                        $icon = 'fa fa-user';
                        break;
                      case 'Files':
                        $icon = 'fa fa-file-pdf';
                        break;
                      default:
                        $icon = 'icon-info';
                        break;
                    }
                  @endphp
                  <div class="col-6 border-bottom">
                    <div class="row" style="margin: 20px 0 20px 0;">
                      <div class="col-2 col-xl-1 text-center d-flex justify-content-center align-items-center">
                        <i class="{{ $icon }}" style="font-size: 25pt;"></i>
                      </div>
                      <div class="col-10 col-xl-11" style="padding: 0;">
                        <a href="{{ $url }}" class="url text-primary">
                          <h6 class="responsive-font">{{ $title }}</h6>
                        </a>
                        <span class="text-muted responsive-font" style="text-transform: capitalize !important">{{ str_replace('_', ' ', $category) }}</span>
                        @if ($type == 'users')
                          <p class="responsive-font">
                            @if ($description)
                              <i class="fa fa-envelope"></i>&nbsp;{{ $description }}
                            @endif
                            @if ($phone)
                              <br/>
                              <i class="fa fa-phone"></i>&nbsp;{{ $phone }}
                            @endif
                          </p>
                        @else
                          {{-- <p>{{ str_limit(strip_tags($description), $limit = 100, $end = '...') }}</p> --}}
                          <p class="one-line ellipsis responsive-font">{{ strip_tags($description) }}</p>
                        @endif
                      </div>
                    </div>
                </div>
                @endforeach
                {{-- @foreach($searchResults as $searchResult)
                <div class="col-6">
                  @php
                    if(is_object($searchResult)){
                      $url = $searchResult->url;
                      $title = $searchResult->title;
                      $category = $searchResult->category;
                      $description = $searchResult->description;
                      $phone = $searchResult->phone;
                      $type = $searchResult->type;
                    }else{
                      $url = $searchResult['url'];
                      $title = $searchResult['title'];
                      $category = $searchResult['category'];
                      $description = $searchResult['description'];
                      $phone = $searchResult['phone'];
                      $type = $searchResult['type'];
                    }

                    switch ($type) {
                      case 'users':
                        $icon = 'fa fa-user';
                        break;
                      case 'Files':
                        $icon = 'fa fa-file-pdf';
                        break;
                      default:
                        $icon = 'icon-info';
                        break;
                    }
                  @endphp
                  <div class="row" style="margin: 20px 0 20px 0;">
                    <div class="col-md-1 text-center">
                      <i class="{{ $icon }}" style="font-size: 25pt;"></i>
                    </div>
                    <div class="col-md-11" style="padding: 0;">
                      <a href="{{ $url }}" class="url text-primary">
                        <h5>{{ $title }}</h5>
                      </a>
                      <span class="text-muted" style="text-transform: capitalize !important">{{ str_replace('_', ' ', $category) }}</span>
                      @if ($type == 'users')
                        <p>
                          @if ($description)
                            <i class="fa fa-envelope"></i>&nbsp;{{ $description }}
                          @endif
                          @if ($phone)
                            <br/>
                            <i class="fa fa-phone"></i>&nbsp;{{ $phone }}
                          @endif
                        </p>
                      @else
                        <p>{{ str_limit(strip_tags($description), $limit = 100, $end = '...') }}</p>
                      @endif
                    </div>
                  </div>
                  <hr style="border: 1px solid rgba(0,0,0,.1)">
                </div>
                  @endforeach --}}
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <style type="text/css">
    .uppercase {
      text-transform: uppercase;
    }

    .url:hover {
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

    $(document).on('keyup', '.carousel-search', function (e){
        e.preventDefault();
        if($(this).val() != ''){
            $.ajax({
                url: '/search',
                type: 'get',
                data: {
                    query: $(this).val()
                },
                success:function(response){
                    $('#autocomplete-container').removeClass('d-none').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                }
            });
        }
    });

    $(document).on('click', '.submit-search', function (e){
        e.preventDefault();
        $('#searh-form').submit();
    });

    $(document).mouseup(function(e){
        var container = $("#autocomplete-container");

        if (!container.is(e.target) && container.has(e.target).length === 0){
            container.addClass('d-none');
        }
    });

    $(document).on('scroll', function (e){
        $("#autocomplete-container").addClass('d-none');
    });

    // CKEDITOR.config.height = 450;
  </script>
  @endsection