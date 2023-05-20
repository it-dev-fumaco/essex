@extends('portal.app')

@section('content')
@include('portal.modals.edit_post_modal')
@include('portal.modals.delete_post_modal')
    <div class="container-fluid">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <div class="col-md-9">
                    <section id="slider">
                        <div class="tp-banner-container">
                           <div class="tp-banner">
                              <ul>
                                 <li data-transition="fade" data-slotamount="7" data-masterspeed="2000" data-thumb="{{ asset('storage/img/slider/businessman.jpg') }}" data-delay="10000">
                                    <img src="{{ asset('storage/img/dummy.png') }}" alt="laptopmockup_sliderdy" data-lazyload="{{ asset('storage/img/slider/businessman.jpg') }}" data-bgposition="right top" data-duration="12000" data-ease="Power0.easeInOut" data-bgfit="115" data-bgfitend="100" data-bgpositionend="center bottom">
                                    <div class="tp-caption largeHeadingWhite sfl str tp-resizeme start" data-x="left" data-y="center" data-voffset="-40" data-voffset="-80" data-speed="1200" data-start="950" data-easing="easeInOutExpo" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="800" data-endeasing="easeInOutExpo" style="z-index: 12; margin-left: 25px; width: 500px; word-wrap: break-word !important;">
                                        <div class="container-fluid">
                                            <h3 style="font-family: 'Trebuchet MS'; text-align: left !important; min-height: 0px; min-width: 0px; line-height: 94px; border-width: 0px; padding: 0px; letter-spacing: 2px; font-size: 24px; text-transform: uppercase; font-weight: 700; color: #fff; display: inline-block !important">Welcome to </h3>
                                            <h3 style="font-family: 'Trebuchet MS'; text-align: left !important; min-height: 0px; min-width: 0px; border-width: 0px; padding: 0px; letter-spacing: 2px; font-size: 24px; text-transform: uppercase; font-weight: 700; color: yellow; display: inline-block !important">Essex 7!</h3>
                                            <form action="/manuals" method="get">
                                                <input type="text" class="form-control carousel-search" type="search" name="search" placeholder="How can we help you today?">
                                            </form>
                                            {{-- <h3 style="font-family: 'Trebuchet MS'; text-align: left !important; min-height: 0px; min-width: 0px; line-height: 50px; border-width: 0px; padding: 0px; letter-spacing: 2px; font-size: 16px; text-transform: uppercase; font-weight: 700; color: #fff; display: inline-block !important">Suggested Search:</h3>
                                            <div>
                                                @for($i = 0; $i < 15; $i++)
                                                    <span class="badge" style="font-size: 16px;">Test</span>
                                                @endfor
                                            </div> --}}
                                        </div>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                    </section>
                    <section id="general-it-concerns" style="margin-top: 10px;">
                        <div class="row">
                            <h3 class="center">General IT Concerns</h3>
                            @foreach ($general_concerns as $concern)
                                <div class="col-md-6" style="padding: 10px 20px 10px 20px;">
                                    <div class="container-fluid card" style="box-shadow: 1px 1px 4px #999999">
                                        <div class="row equal-height">
                                            <div class="col-md-1" style="display: flex; justify-content: center; align-items: center;">
                                                <i class="fa fa-question-circle" style="font-size: 18px;"></i>
                                            </div>
                                            <div class="col-md-11">
                                                <a href="/article/{{ $concern->slug }}">
                                                    <b>{{ $concern->title }}</b><br>
                                                    <small class="text-muted">{{ $concern->short_text }}</small>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
                <div class="col-md-3">
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
                            <span style="font-size: 12pt; font-weight: 700 !important">My Approvals</span>
                            <hr style="border: 1px solid rgba(175, 175, 175, .4); margin: 10px !important">
                            @forelse ($approvals as $approval)
                                <p style="margin-bottom: 5px;"><span class="custom-badge {{ $approval->status == 'APPROVED' ? 'badge-success' : 'badge-warning' }}">{{ $approval->status }}</span> {{ $approval->leave_type }} on {{ Carbon\Carbon::parse($approval->date_from)->format('M d, Y') }}</p>
                            @empty
                                <p class="center" style="margin-bottom: 5px;">You have no pending approvals</p>
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
            {{-- <div class="row" style="margin-top: 10px;">
                <div class="col-md-9">
                    <div class="row">
                        <h3 class="center">General IT Concerns</h3>
                        @foreach ($general_concerns as $concern)
                            <div class="col-md-4" style="padding: 10px 20px 10px 20px;">
                                <div class="container-fluid card card-kb">
                                    <div>
                                        <a href="/article/{{ $concern->slug }}">
                                            <b>{{ $concern->title }}</b>
                                        </a>
                                    </div>
                                    <hr style="border: 1px solid rgba(175, 175, 175, .4); margin: 10px !important">
                                    <div class="text-concat">
                                        {{ $concern->short_text }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-3">
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
            </div> --}}
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
        .card{
           padding: 15px;
           border-radius: 5px;
        }

        .card-kb{
            min-height: 175px;
            border: 1px solid rgba(175, 175, 175, .4);
            border-top: 2px solid #4CAF50;
        }
        .border-danger{
           border: 1px solid red;
        }
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

        .card-greeting{
            background-color: #11703C;
            color: #fff;
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
            border-radius: 25px;
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
    </script>
@endsection