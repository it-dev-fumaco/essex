@extends('emails.layouts.master')

@section('title', 'Welcome — ' . config('app.name'))

@section('preheader')
    Your core systems access overview and quick links — welcome aboard.
@endsection

@section('header_tagline')
    Employee onboarding
@endsection

@section('email_heading')
    Welcome, {{ $data['name'] ?? 'Employee' }}
@endsection

@section('content')
    <p style="margin:0 0 16px;">Welcome aboard to <strong style="color:#0f172a;">{{ config('app.name') }}</strong>! We are glad you are joining the team and look forward to your contributions.</p>

    <p style="margin:0 0 20px;">
        Below are the primary platforms you will use. Use the buttons to open each system (your network may require the LAN address).
    </p>

    <p style="margin:0 0 8px; font-size:13px; font-weight:700; letter-spacing:0.02em; text-transform:uppercase; color:#64748b;">ERP (Enterprise Resource Planning)</p>
    <p style="margin:0 0 12px; font-size:14px; color:#475569;">Core system for company-wide data and business processes.</p>
    @include('emails.partials.button', ['url' => 'http://erp.fumaco.com', 'label' => 'Open ERP (hostname)', 'marginBottom' => '8px'])
    @include('emails.partials.button', ['url' => 'http://10.0.0.83', 'label' => 'Open ERP (10.0.0.83)', 'marginBottom' => '24px'])

    <p style="margin:0 0 8px; font-size:13px; font-weight:700; letter-spacing:0.02em; text-transform:uppercase; color:#64748b;">AthenaERP</p>
    <p style="margin:0 0 12px; font-size:14px; color:#475569;">Inventory and stock tracking.</p>
    @include('emails.partials.button', ['url' => 'http://athena.fumaco.com', 'label' => 'Open AthenaERP (hostname)', 'marginBottom' => '8px'])
    @include('emails.partials.button', ['url' => 'http://10.0.0.79', 'label' => 'Open AthenaERP (10.0.0.79)', 'marginBottom' => '24px'])

    <p style="margin:0 0 8px; font-size:13px; font-weight:700; letter-spacing:0.02em; text-transform:uppercase; color:#64748b;">MES (Manufacturing Execution System)</p>
    <p style="margin:0 0 12px; font-size:14px; color:#475569;">Production orders and shop-floor monitoring.</p>
    @include('emails.partials.button', ['url' => 'http://mes.fumaco.local', 'label' => 'Open MES (hostname)', 'marginBottom' => '8px'])
    @include('emails.partials.button', ['url' => 'http://10.0.0.81', 'label' => 'Open MES (10.0.0.81)', 'marginBottom' => '24px'])

    <p style="margin:0 0 8px; font-size:13px; font-weight:700; letter-spacing:0.02em; text-transform:uppercase; color:#64748b;">Essex — Employee portal</p>
    <p style="margin:0 0 12px; font-size:14px; color:#475569;">Leave credits, absence notices, and HR self-service.</p>
    @include('emails.partials.button', ['url' => 'https://essex.fumaco.local', 'label' => 'Open Essex (hostname)', 'marginBottom' => '8px'])
    @include('emails.partials.button', ['url' => 'https://10.0.0.5', 'label' => 'Open Essex (10.0.0.5)', 'marginBottom' => '20px'])

    <p style="margin:0 0 12px;">Our IT team will follow up with login credentials and initial password setup.</p>

    <p style="margin:0;">If you have immediate questions, reply to this message or contact IT.</p>
@endsection

@section('footer_line')
    IT Department — {{ config('app.name') }}
@endsection
