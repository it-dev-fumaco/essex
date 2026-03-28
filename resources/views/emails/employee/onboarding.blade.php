@extends('emails.layouts.master')

@section('title', 'New hire provisioning — ' . config('app.name'))

@section('preheader')
    New hire: provision accounts and hardware for start date {{ $data['start_date'] ?? ($data['joining_date'] ?? '') }}.
@endsection

@section('header_tagline')
    IT &amp; HR notification
@endsection

@section('email_heading')
    New hire — account provisioning
@endsection

@section('content')
    <p style="margin:0 0 16px;">Good day,</p>

    <p style="margin:0 0 20px;">
        A new team member is joining. Please complete standard account provisioning and hardware setup so they are ready by
        <strong style="color:#0f172a;">{{ $data['start_date'] ?? ($data['joining_date'] ?? '') }}</strong>.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 20px; border-collapse:collapse;">
        <tr>
            <td style="padding:14px 16px; background-color:#f1f5f9; border-radius:6px; border:1px solid #e2e8f0;">
                <p style="margin:0 0 10px; font-size:13px; font-weight:700; letter-spacing:0.02em; text-transform:uppercase; color:#64748b;">Employee details</p>
                <p style="margin:0 0 6px;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Name</span> <strong style="color:#0f172a;">{{ $data['full_name'] ?? ($data['name'] ?? '') }}</strong></p>
                <p style="margin:0 0 6px;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Position</span> <strong style="color:#0f172a;">{{ $data['job_title'] ?? ($data['role'] ?? '') }}</strong></p>
                <p style="margin:0 0 6px;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Department</span> <strong style="color:#0f172a;">{{ $data['department'] ?? '' }}</strong></p>
                <p style="margin:0 0 6px;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Reporting manager</span> <strong style="color:#0f172a;">{{ $data['manager_name'] ?? '' }}</strong></p>
                <p style="margin:0;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Primary location</span> <strong style="color:#0f172a;">{{ $data['primary_location'] ?? '' }}</strong></p>
            </td>
        </tr>
    </table>

    <h2 style="margin:0 0 10px; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:700; line-height:1.35; color:#0f172a;">Required access &amp; software</h2>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Email &amp; workspace</span> Microsoft 365 / Google Workspace (standard).</p>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">ERP / internal</span> Standard permissions for {{ $data['erp_system_name'] ?? 'ERP' }}.</p>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Security groups</span> Add to {{ $data['department'] ?? '' }} DL and shared drive.</p>
    <p style="margin:0 0 20px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">VPN / remote</span> {{ $data['vpn_remote_access'] ?? 'Yes' }}</p>

    <h2 style="margin:0 0 10px; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:700; line-height:1.35; color:#0f172a;">Hardware requirements</h2>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Workstation</span> Standard IT-issued laptop or desktop.</p>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Peripherals</span> Monitor, mouse, keyboard, headset.</p>
    <p style="margin:0 0 20px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Phone / extension</span> {{ $data['extension'] ?? 'N/A' }}</p>

    <h2 style="margin:0 0 10px; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:700; line-height:1.35; color:#0f172a;">Security checklist</h2>
    <p style="margin:0 0 6px;">[ ] Enroll in MFA.</p>
    <p style="margin:0 0 6px;">[ ] Assign initial security training (LMS).</p>
    <p style="margin:0;">[ ] Verify workstation meets hardening policy.</p>

    <p style="margin:24px 0 0;">Thank you.</p>
@endsection

@section('footer_line')
    {{ config('app.name') }} — Internal use
@endsection
