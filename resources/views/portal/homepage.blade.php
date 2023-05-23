@extends('portal.app')

@section('content')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
    <div class="container-fluid">
        <div class="col-md-12 col-sm-10">
            <div class="row">
                <div class="col-md-9">
                    <div id="imagecontainer" class="container-fluid">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <h3 style="font-family: 'Trebuchet MS'; text-align: left !important; min-height: 0px; min-width: 0px; line-height: 94px; border-width: 0px; padding: 0px; letter-spacing: 2px; font-size: 28px; text-transform: uppercase; font-weight: 700; color: #fff; display: inline-block !important">Welcome to </h3>
                                <h3 style="font-family: 'Trebuchet MS'; text-align: left !important; min-height: 0px; min-width: 0px; border-width: 0px; padding: 0px; letter-spacing: 2px; font-size: 28px; text-transform: uppercase; font-weight: 700; color: yellow; display: inline-block !important">Essex!</h3>
                            </div>
                            <div class="col-md-8">
                                <form action="{{ route('search') }}" id="searh-form" method="POST">
                                    @csrf
                                    <div class="row" style="padding: 0; margin:0 ">
                                        <div class="col-md-9" style="padding: 0; margin: 0">
                                            <input type="text" class="form-control carousel-search" type="search" name="query" placeholder="How can we help you today?" autocomplete="off">
                                        </div>
                                        <div class="col-md-3" style="padding: 0; margin: 0">
                                            <button type="submit" class="btn bg-success" style="padding: 16px; width: 100%; border-radius: 0 25px 25px 0; font-weight: 700">Search</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="autocomplete-container" class="card bg-white border-secondary d-none"></div>
                            </div>
                        </div>
                    </div>
                    <section id="tbl-manuals" style="margin-top: 10px; z-index: 999 !important"></section>
                </div>
                <div class="col-md-3" style="padding: 0 20px 0 50px;">
                    <div class="card card-greeting">
                        <div class="row">
                            <div class="col-md-3 col-md-offset-1">
                                <i class="fa fa-cloud" style="font-size: 30pt;"></i>
                            </div>
                            @php
                                $greet = 'Morning';
                                if(Carbon\Carbon::now()->format('A') == 'PM'){
                                    $greet = Carbon\Carbon::now()->format('H') >= 17 ? 'Evening' : 'Afternoon';
                                }
                            @endphp
                            <div class="col-md-8" style="padding: 0;">
                                <h5>Good {{ $greet }}</h5>
                                <span style="display: block; font-size: 10pt; font-weight: 600">{{ Carbon\Carbon::now()->format('l') }}</span>
                                <span style="display: block; font-size: 10pt; font-weight: 600">{{ Carbon\Carbon::now()->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    @if (Auth::check())
                        <div class="card card-border" style="margin-top: 10px; color: #001032; border: 1px solid #001032; text-align: left">
                            <span style="font-size: 12pt; font-weight: 700 !important">Pending for Approval</span>
                            <hr style="border: 1px solid rgba(175, 175, 175, .4); margin: 10px !important">
                            @forelse ($approvals as $i => $approval)
                                @php
                                    $date = 'on '.Carbon\Carbon::parse($approval->date_from)->format('M d, Y');
                                    if(Carbon\Carbon::parse($approval->date_from)->format('M d, Y') != Carbon\Carbon::parse($approval->date_to)->format('M d, Y')){
                                        $date = 'from '.Carbon\Carbon::parse($approval->date_from)->format('M d, Y').' to '.Carbon\Carbon::parse($approval->date_to)->format('M d, Y');
                                    }
                                @endphp
                                <a href="#" data-toggle="modal" data-target="#approval-modal-{{ $i }}" style="margin-bottom: 5px; text-decoration: none; text-transform: none; color: #000;">&nbsp;●&nbsp;{{ $approval->leave_type }} {{ $date }}</a>

                                <!-- The modal -->
                                <div class="modal fade" id="approval-modal-{{ $i }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="modalLabel">
                                                    {{ $approval->leave_type }} {{ $date }}
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        Type of Absence: <b>{{ $approval->leave_type }}</b><br/>
                                                        Status: <b>{{ $approval->status }}</b>
                                                    </div>
                                                    <div class="col-md-6">
                                                        From: <b>{{ Carbon\Carbon::parse($approval->date_from)->format('M d, Y').' '.Carbon\Carbon::parse($approval->time_from)->format('h:i A') }}</b><br/>
                                                        To: <b>{{ Carbon\Carbon::parse($approval->date_to)->format('M d, Y').' '.Carbon\Carbon::parse($approval->time_to)->format('h:i A') }}</b>
                                                    </div>
                                                    <div class="col-md-12" style="margin-top: 10px;">
                                                        Reported to: <b>{{ $approval->info_by }}</b><br/>
                                                        Reason: <b>{{ $approval->reason }}</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn bg-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="center" style="margin-bottom: 5px;">You have no pending for approval</p>
                            @endforelse
                        </div>
                    @endif

                    <div class="card card-border" style="margin-top: 10px; color: #313B99; border: 1px solid #313B99; text-align: center">
                        <span style="font-size: 12pt; font-weight: 700 !important">Our Mission</span>
                        <hr style="border: 1px solid rgba(175, 175, 175, .4); margin: 10px !important">
                        To design and provide excellent, affordable, quality, energy efficient lighting solutions that doesn't jeopardize the environment
                    </div>
               
                    <div class="card card-border" style="margin-top: 10px; color: #D55E33; border: 1px solid #D55E33; text-align: center">
                        <span style="font-size: 12pt; font-weight: 700 !important">Our Vision</span>
                        <hr style="border: 1px solid rgba(175, 175, 175, .4); margin: 10px !important">
                        FUMACO is the leading lighting solutions provider in the Philippines and in the ASEAN Region manned by highly motivated and equipped people.
                        <br>
                        <br>
                        We drive new technologies and standards throughout our organization and the industry, lifting and inspiring the various stakeholders around us.
                    </div>

                    <div class="alert alert-info" style="margin-top: 10px;">
                        <h4>REMINDER</h4>
                        <br>
                        <b>1. FIRST TIME USERS - please read the <a href="/article/{{ $it_policy }}" style="color: inherit; text-decoration: underline">IT Guidelines and Policies</a>.</b>
                        <p>2. Shutdown computers, and turn off monitors, printers, photocopiers, laptops, AVR s(Automatic voltage regulators) and transformers.</p>
                        <p>3. Log off each terminal after use</p>
                    </div>
                    <div class="alert alert-warning" style="margin-top: 15px;">
                        <h4>Need for Support?</h4>
                        <br>
                        <p>If you cannot find an answer in the knowledgebase, email IT at <b>it@fumaco.local</b> or <b>it@fumaco.com</b></p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    @include('portal.includes.events')
    @include('portal.includes.milestones')
    {{-- @include('portal.includes.instructions') --}}
    @if (session()->has('notice_data'))
        @php
            $data = session()->get('notice_data');
        @endphp
        <div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="center" style="background-color: {{ $data['success'] == 1 ? '#20BD67;' : '#767F86;' }} padding: 20px 0 20px 0; color: #fff;">
                        <i class="fa fa-check-circle-o" style="font-size: 50pt; font-weight: 500 !important"></i>
                        <br>
                        <h2>{{ $data['status'] ? $data['status'] : null }}</h2>
                    </div>
                    <div class="center" style="padding: 20px 0 20px 0;">
                        <p style="font-size: 12pt;">{!! $data['message'] !!}</p>
                        @if(isset($data['approved_by']) && $data['approved_by'])
                            <p>{{ ($data['status'] == 'APPROVED' ? 'Approved by: ' : 'Disapproved by: ').$data['approved_by'] }}</p>
                        @endif
                        @if(isset($data['approved_date']) && $data['approved_date'])
                            <p>Date {{ ($data['status'] == 'APPROVED' ? 'Approved: ' : 'Disapproved: ').$data['approved_date'] }}</p>
                        @endif
                        <br>
                        <button class="btn btn-warning" style="border-radius: 25px;" data-dismiss="modal" aria-label="Close">OK</button>
                    </div>
                </div>
            </div>
            </div>
        </div> 
    @endif

    <style>
        .text-concat {
           position: relative;
           display: inline-block;
           word-wrap: break-word;
           overflow: hidden;
           max-height: 4.5em;
           line-height: 1.6em;
           text-align: left;
           font-size: 10pt !important;
        }
        .icon-test:before {
            content: "\f648";
        }

        .equal-height {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
        }
        .equal-height > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }
        .carousel-search{
            border-radius: 25px 0 0 25px;
            font-family: 'Trebuchet MS';
            font-size: 17px;
        }
        .custom-badge{
            border-radius: 5px;
            padding: 5px;
            font-size: 9pt;
            font-weight: 600;
        }

        .badge-warning{
            color: #000;
            background-color: #FFC107;
        }

        .badge-success{
            color: #fff;
            background-color: #28A745;
        }

        #imagecontainer {
            background: url("{{ asset('storage/img/slider/businessman.jpg') }}") no-repeat;
            height: 175px;
            /* border: 1px solid; */
            background-size: 100% auto;
            background-repeat: no-repeat;
            background-position: center center;
        }
     </style>
@endsection

@section('script')

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
    <script>
        $(document).ready(function () {
            @if (session()->has('notice_data'))
                $('#noticeModal').modal('show');
            @endif
        });
    	 $(document).on('click', '#editPostBtn', function(event){
        event.preventDefault();
        $('#editPostModal .post_id').val($(this).data('id'));
        $('#editPostModal .post_title').val($(this).data('title'));
        // $('#editPostModal .post_content').val($(this).data('content'));
        $('#editPostModal .original_post_image').val($(this).data('image'));
        $('#editPostModal .original_post_title').val($(this).data('title'));
        $('#editPostModal .original_post_content').val($(this).data('content'));
        CKEDITOR.instances['post_content'].setData($(this).data('content'));

        $('#editPostModal').modal('show');
    });
    $(document).on('click', '#deletePostBtn', function(event){
        event.preventDefault();
        $('#deletePostModal .post_id').val($(this).data('id'));
        $('#deletePostModal .post_title').text($(this).data('title'));
        $('#deletePostModal').modal('show');
    });
        {{-- $('textarea').ckeditor(); --}}
        // $('.textarea').ckeditor(); // if class is prefered.

        CKEDITOR.config.height = 450;

    $(document).on('click', '.add-tag', function (e){
        e.preventDefault();
        var current_tags = $('.tag-input').val();
        var tag_id = current_tags == '' ? $(this).data('id') : ',' + $(this).data('id'); 
        if($.inArray($(this).data('id'), current_tags.split(',')) === -1){
            $('.tag-input').val(current_tags + tag_id);
            load_manuals();
        }
    });

    $(document).on('click', '.remove-tag', function (e){
        e.preventDefault();
        var current_tags = $('.tag-input').val();
        var tag_id = $(this).data('id');
        var value = '';
        $.each(current_tags.split(','), function(i, val){
            if(val != tag_id && val != ''){
                value += val + ',';
            }
        });
        $('.tag-input').val(value);
        load_manuals();
    });

    $(document).on('keyup', '.carousel-search', function (e){
        e.preventDefault();
        if($(this).val() != ''){
            $.ajax({
                url: '/search',
                type: 'POST',
                data: {
                    query: $(this).val(),
                    _token: '{{ csrf_token() }}'
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

    load_manuals();
    function load_manuals(){
        $.ajax({
            url:"/tbl_manuals",
            type:"GET",
            data: {
                tags: $('.tag-input').val()
            },
            success:function(response){
                $('#tbl-manuals').html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }
    
    </script>
@endsection