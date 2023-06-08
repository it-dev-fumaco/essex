@extends('portal.app')

@section('content')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
@php
    $week = [
        0 => 'Sun',
        1 => 'Mon',
        2 => 'Tue',
        3 => 'Wed',
        4 => 'Thu',
        5 => 'Fri',
        6 => 'Sat',
    ];
@endphp
    <div class="container-fluid">
        <div class="col-md-12 col-sm-10">
            <div class="row">
                <div class="col-9">
                    <div id="imagecontainer" class="container-fluid">
                        <div class="container-fluid">
                            <div class="col-md-12">
                                <h3 style="font-family: 'Trebuchet MS'; text-align: left !important; min-height: 0px; min-width: 0px; line-height: 94px; border-width: 0px; padding: 0px; letter-spacing: 2px; font-size: 28px; text-transform: uppercase; font-weight: 700; color: #fff; display: inline-block !important">Welcome to </h3>
                                <h3 style="font-family: 'Trebuchet MS'; text-align: left !important; min-height: 0px; min-width: 0px; border-width: 0px; padding: 0px; letter-spacing: 2px; font-size: 28px; text-transform: uppercase; font-weight: 700; color: yellow; display: inline-block !important">Essex!</h3>
                            </div>
                            <div class="col-md-8">
                                <form action="{{ route('search') }}" id="searh-form" method="get">
                                    <div class="row p-0 m-0">
                                        <div class="col-md-9 p-0 m-0">
                                            <input type="text" class="form-control carousel-search" type="search" name="query" placeholder="How can we help you today?" autocomplete="off">
                                        </div>
                                        <div class="col-md-3 p-0 m-0">
                                            <button type="button" class="btn h-100 w-100 fw-bold submit-search" style="border-radius: 0 25px 25px 0; background-color: #0dc761">Search</button>
                                        </div>
                                    </div>
                                </form>
                                <div id="autocomplete-container" class="container w-50 p-0 d-none"></div>
                            </div>
                        </div>
                    </div>
                    <section id="tbl-manuals" class="mt-2 p-2" style="z-index: 999 !important"></section>
                    <section id="videos-container" style="margin-top: 10px; z-index: 999 !important">
                        <div class="container-fluid p-0">
                            @php
                                $videos_array = [];
                                if (Storage::disk('public')->exists('videos/IT-Guidelines-and-Policy-09-20-2017.mp4')){
                                    $videos_array[0] = [
                                        'title' => 'IT Guidelines and Policies',
                                        'url' => 'storage/videos/IT-Guidelines-and-Policy-09-20-2017.mp4',
                                        'thumbnail' => 'storage/thumbnail/it_guidelines.png'
                                    ];
                                }

                                if (Storage::disk('public')->exists('videos/Internet-Services-Proxy-Server-Configuration 09-20-2017.mp4')){
                                    $videos_array[1] = [
                                        'title' => 'Internet Services Proxy Configuration',
                                        'url' => 'storage/videos/Internet-Services-Proxy-Server-Configuration 09-20-2017.mp4',
                                        'thumbnail' => 'storage/thumbnail/internet_services.png'
                                    ];
                                }
                            @endphp
                            <div class="row">
                                <div class="col-4 p-3">
                                    <div class="card h-100 shadow" style="border-top: 3px solid #0D6EFD">
                                        <div class="card-header">
                                            <span class="fw-bold" style="font-size: 12pt;">Reminder</span>
                                        </div>
                                        <div class="card-body">
                                            <b>1. FIRST TIME USERS - please read the <a href="/article/{{ $it_policy }}" style="color: inherit; text-decoration: underline">IT Guidelines and Policies</a>.</b>
                                            <p>2. Shutdown computers, and turn off monitors, printers, photocopiers, laptops, AVR s(Automatic voltage regulators) and transformers.</p>
                                            <p>3. Log off each terminal after use</p>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($videos_array as $video)
                                    <div class="col-4 p-3">
                                        <div class="card thumbnail h-100 shadow" data-title="{{ $video['title'] }}" data-url="{{ asset($video['url']) }}">
                                            <div class="card-body p-0">
                                                <div class="h-100 position-relative" style="background-color: #000">
                                                    <img src="{{ $video['thumbnail'] }}" class="w-100 h-100" style="opacity: .9">
                                                    <i class="fa fa-play-circle-o video-play-icon absolute-center"></i>
                                                </div>
                                            </div>
                                            {{-- <div class="card-footer fw-bold">
                                                <span>{{ $video['title'] }}</span><br/>
                                                <span class="text-muted">General IT Concern</span>
                                            </div> --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                    {{-- <section id="tbl-manuals" class="mt-2 p-2" style="z-index: 999 !important"></section> --}}
                </div>
                <div class="col-3">
                    {{-- <div class="card card-greeting">
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
                    </div> --}}

                    <div id="carouselExampleCaptions" class="carousel slide mt-2 shadow" data-bs-ride="carousel">
                        <div class="carousel-inner" style="border-radius: 5px;">
                            <div class="carousel-item bg-dark active" style="min-height: 350px;">
                                <img src="{{ asset('storage/img/slider/achievement.jpg') }}" class="d-block w-100" style="height: 350px; object-fit: cover; opacity: .28">
                                <div class="carousel-caption d-none d-md-block" style="top: 45%; transform: translateY(-50%);">
                                    <h5 style="text-shadow: 2px 2px 8px #000">Mission</h5>
                                    <br>
                                    <p style="text-shadow: 2px 2px 8px #000">To design and provide excellent, affordable, quality, energy efficient lighting solutions that doesn't jeopardize the environment.</p>
                                </div>
                            </div>
                            <div class="carousel-item bg-dark" style="min-height: 350px;">
                                <img src="{{ asset('storage/img/slider/achievement.jpg') }}" class="d-block w-100" style="height: 350px; object-fit: cover; opacity: .28">
                                {{-- <img src="{{ asset('storage/img/featured/3.jpg') }}" class="d-block w-100" style="height: 350px; object-fit: cover;"> --}}
                                <div class="carousel-caption d-none d-md-block" style="top: 38%; transform: translateY(-50%);">
                                    <h5 style="text-shadow: 2px 2px 4px #000">Vision</h5>
                                    <p style="text-shadow: 2px 2px 8px #000"><b>FUMACO</b> is the leading lighting solutions provider in the Philippines and in the ASEAN Region manned by highly motivated and equipped people.</p>
                                    <br>
                                    <p style="text-shadow: 2px 2px 8px #000">We drive new technologies and standards throughout our organization and the industry, lifting and inspiring the various stakeholders around us.</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <div class="card mt-2 shadow" style="border-top: 3px solid #11703C">
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $greet = 'Morning';
                                    if(Carbon\Carbon::now()->format('A') == 'PM'){
                                        $greet = Carbon\Carbon::now()->format('H') >= 17 ? 'Evening' : 'Afternoon';
                                    }
                                @endphp
                                <div class="col-5" style="display: flex; justify-content: center; align-items: center;">
                                    <div class="text-center">
                                        <i class="fa fa-cloud" style="font-size: 30pt;"></i>
                                        <span class="fw-bold d-block" style="font-size: 10pt;">Good {{ $greet }}!</span>
                                    </div>
                                </div>
                                <div class="col-7" style="display: flex; justify-content: center; align-items: center;">
                                    <div class="text-center">
                                        <span id="current-time" class="fw-bold d-block" style="font-size: 2.5rem;"></span>
                                        <span id="current-date" class="fw-bold d-block mt-2" style="font-size: 11pt;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (Auth::check())
                        <div class="card mt-2 shadow" style="text-align: left; border-top: 3px solid #FFC414">
                            <div class="card-header">
                                <span style="font-size: 12pt; font-weight: 700 !important">Pending for Approval</span>
                            </div>
                            <div class="card-body">
                                @forelse ($approvals as $i => $approval)
                                    @php
                                        $date = 'on '.Carbon\Carbon::parse($approval->date_from)->format('M d, Y');
                                        if(Carbon\Carbon::parse($approval->date_from)->format('M d, Y') != Carbon\Carbon::parse($approval->date_to)->format('M d, Y')){
                                            $date = 'from '.Carbon\Carbon::parse($approval->date_from)->format('M d, Y').' to '.Carbon\Carbon::parse($approval->date_to)->format('M d, Y');
                                        }
                                    @endphp
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#approval-modal-{{ $i }}" style="margin-bottom: 5px; text-decoration: none; text-transform: none; color: #000;">&nbsp;●&nbsp;{{ $approval->leave_type }} {{ $date }}</a> <br>

                                    <!-- The modal -->
                                    <div class="modal fade" id="approval-modal-{{ $i }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ $approval->leave_type }} {{ $date }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                    <button type="button" class="btn bg-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="center" style="margin-bottom: 5px;">You have no pending for approval</p>
                                @endforelse
                                <hr>
                                <div class="container-fluid" style="padding: 0 !important">
                                    <span style="font-size: 10pt; font-weight: 700 !important">My Leave Approver(s)</span>
                                    @foreach ($approvers as $approver)
                                        @if ($approver->employee_id == Auth::user()->user_id)
                                            @continue
                                        @endif
                                        @php
                                            $image = $approver->image ? $approver->image : 'storage/img/user.png';
                                            if(!Storage::disk('public')->exists(str_replace('storage/', null, $image))){
                                                $image = 'storage/img/user.png';
                                            }
                                        @endphp
                                        <div class="row container-fluid" style="display: flex; justify-content: center; align-items: center;">
                                            <div class="col-md-2" style="padding: 5px !important">
                                                <img src="{{ asset($image) }}" style="width: 100% !important;">
                                            </div>
                                            <div class="col-md-9">
                                                <span style="font-weight: 600; font-size: 9pt;">{{ $approver->employee_name }}</span><br>
                                                <cite style="font-size: 8pt;">{{ $approver->designation }}</cite>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card mt-2 shadow" style="text-align: left; border-top: 3px solid #FFC414">
                        <div class="card-header">
                            <span class="fw-bold" style="font-size: 12pt;">Need for Support?</span>
                        </div>
                        <div class="card-body">
                            {{-- <b>1. FIRST TIME USERS - please read the <a class="text-decoration-underline" href="/article/{{ $it_policy }}" style="color: inherit;">IT Guidelines and Policies</a>.</b>
                            <p>2. Shutdown computers, and turn off monitors, printers, photocopiers, laptops, AVR s(Automatic voltage regulators) and transformers.</p>
                            <p>3. Log off each terminal after use</p>
                            <br> --}}
                            <p>If you cannot find an answer in the knowledgebase, email IT at <b>it@fumaco.local</b> or <b>it@fumaco.com</b></p>
                        </div>
                    </div>

                    {{-- <div class="card card-border" style="margin-top: 10px; color: #313B99; border: 1px solid #313B99; text-align: center">
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
                    </div> --}}

                    {{-- <div class="card mt-2" style="border-top: 3px solid #0D6EFD">
                        <div class="card-header">
                            <span style="font-size: 12pt; font-weight: 700 !important">Reminder</span>
                        </div>
                        <div class="card-body">
                            <b>1. FIRST TIME USERS - please read the <a href="/article/{{ $it_policy }}" style="color: inherit; text-decoration: underline">IT Guidelines and Policies</a>.</b>
                            <p>2. Shutdown computers, and turn off monitors, printers, photocopiers, laptops, AVR s(Automatic voltage regulators) and transformers.</p>
                            <p>3. Log off each terminal after use</p>
                        </div>
                    </div> --}}

                    {{-- <div class="card mt-2" style="border-top: 3px solid #0D6EFD">
                        <div class="card-header">
                            <span style="font-size: 12pt; font-weight: 700 !important">Need for Support?</span>
                        </div>
                        <div class="card-body">
                            <p>If you cannot find an answer in the knowledgebase, email IT at <b>it@fumaco.local</b> or <b>it@fumaco.com</b></p>
                        </div>
                    </div> --}}

                    {{-- <div class="alert alert-info" style="margin-top: 10px;">
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
                    </div> --}}
                </div>
            </div>
        </div>
        
    </div>

    <div class="modal fade" id="thumbnail-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" style="width: 50% !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel"></h4>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video width="100%" controls>
                        <source src="" type="video/mp4">
                        Your browser does not support the video tag.
                    </video> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-secondary" data-bs-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                </div>
            </div>
        </div>
    </div>
    @include('portal.includes.events')
    @include('portal.includes.milestones')
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

        CKEDITOR.config.height = 450;

    $

    $(document).on('click', '.category-checkbox', function (e){
        $.ajax({
            url: '/tbl_manuals',
            type: 'get',
            data: $('#manuals-form').serialize(),
            success:function(response){
                $('#tbl-manuals').html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
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
        if($('.carousel-search').val() != ''){
            $('#searh-form').submit();
        }
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

    $(document).on('click', '.thumbnail', function (e){
        e.preventDefault();
        var url = $(this).data('url');
        var title = $(this).data('title');

        $('#thumbnail-modal .modal-title').text(title);
        $('#thumbnail-modal source').attr('src', url);
        $('#thumbnail-modal video').get(0).load();
        $('#thumbnail-modal video').get(0).play();
        $('#thumbnail-modal').modal('show');
    });

    $('#thumbnail-modal').on('hidden.bs.modal', function (e) {
        $('#thumbnail-modal video').get(0).pause();
        $('#thumbnail-modal video').get(0).currentTime = 0;
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

    load_time();
    function load_time(){
        setInterval(function() {
            var d = new Date();
            var date_options = { year: 'numeric', month: 'short', day: 'numeric' };
            var time_options = { hour: 'numeric', minute: 'numeric' };
            $('#current-date').html('{{ isset($week[Carbon\Carbon::now()->dayOfWeek]) ? $week[Carbon\Carbon::now()->dayOfWeek] : null }}, ' + d.toLocaleDateString('en-US', date_options));
            $('#current-time').html(d.toLocaleTimeString('en-US', time_options));
        }, 1000);
    }
    
    </script>
@endsection