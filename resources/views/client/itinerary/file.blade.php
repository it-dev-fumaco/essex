@extends('portal.app')

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/js/datepicker/bootstrap-datepicker.css') }}" />
@endpush

@section('content')
<div class="main-container" style="background-color: #EFF3F6;">
    <div class="section" style="padding-top: 20px !important">
        <div class="container-fluid">
            <div class="col-12 col-xl-10 mx-auto">
                <div class="d-flex align-items-center mb-3">
                    <a href="{{ url('/home') }}" class="text-decoration-none me-3" title="Back to home">
                        <i class="fa fa-arrow-circle-left" style="font-size: 28px; color: #1f2a44;"></i>
                    </a>
                    <h1 class="title-2 mb-0" style="margin: 0; letter-spacing: .5pt; font-size: 20pt; border: 0;">Itinerary Notice</h1>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        @include('client.itinerary._form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('css/js/datepicker/bootstrap-datepicker.js') }}"></script>
@include('client.itinerary._assets')
@endsection
