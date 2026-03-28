@extends('emails.layouts.master')

@section('title', 'Offboarding — ' . config('app.name'))

@section('preheader')
    Offboarding: revoke access and recover assets by {{ $data['last_working_date'] ?? ($data['last_day'] ?? '') }}.
@endsection

@section('header_tagline')
    IT &amp; security notification
@endsection

@section('email_heading')
    Employee offboarding — action required
@endsection

@section('content')
    <p style="margin:0 0 16px;">Good day,</p>

    <p style="margin:0 0 20px;">
        Please begin the standard offboarding procedure for the employee below. Complete account deactivation and hardware recovery by
        <strong style="color:#0f172a;">{{ $data['last_working_date'] ?? ($data['last_day'] ?? '') }}</strong>
        to protect our security posture and infrastructure.
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 20px; border-collapse:collapse;">
        <tr>
            <td style="padding:14px 16px; background-color:#f1f5f9; border-radius:6px; border:1px solid #e2e8f0;">
                <p style="margin:0 0 10px; font-size:13px; font-weight:700; letter-spacing:0.02em; text-transform:uppercase; color:#64748b;">Employee details</p>
                <p style="margin:0 0 6px;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Name</span> <strong style="color:#0f172a;">{{ $data['full_name'] ?? ($data['name'] ?? '') }}</strong></p>
                <p style="margin:0 0 6px;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Position</span> <strong style="color:#0f172a;">{{ $data['job_title'] ?? ($data['role'] ?? '') }}</strong></p>
                <p style="margin:0 0 6px;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Department</span> <strong style="color:#0f172a;">{{ $data['department'] ?? '' }}</strong></p>
                <p style="margin:0;"><span style="display:inline-block; min-width:132px; color:#64748b; font-size:13px;">Primary location</span> <strong style="color:#0f172a;">{{ $data['primary_location'] ?? '' }}</strong></p>
            </td>
        </tr>
    </table>

    <h2 style="margin:0 0 10px; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:700; line-height:1.35; color:#0f172a;">Access revocation &amp; deactivation</h2>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Email</span> Disable Microsoft 365; set auto-reply if requested by manager.</p>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">ERPNext</span> Deactivate user; revoke module permissions.</p>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">AthenaERP / MES / Essex</span> Terminate sessions; disable login.</p>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">VPN / remote</span> Remove VPN access.</p>
    <p style="margin:0 0 20px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">DL / groups</span> Remove from distribution lists and shared drives.</p>

    <h2 style="margin:0 0 10px; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:700; line-height:1.35; color:#0f172a;">Hardware &amp; asset recovery</h2>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Workstation</span> Retrieve laptop/desktop and peripherals.</p>
    <p style="margin:0 0 8px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Mobile / other</span> Phone, tokens, RSA keys (if issued).</p>
    <p style="margin:0 0 20px;"><span style="color:#64748b; font-size:13px; display:inline-block; min-width:140px;">Inspection</span> Check for damage or unauthorized software.</p>

    <h2 style="margin:0 0 10px; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:700; line-height:1.35; color:#0f172a;">Security checklist</h2>
    <p style="margin:0 0 6px;">[ ] MFA: disable for this user.</p>
    <p style="margin:0 0 6px;">[ ] LMS: deactivate portal profile.</p>
    <p style="margin:0;">[ ] Data: back up critical local data to shared drive before wipe.</p>

    <p style="margin:24px 0 0;">Thank you.</p>
@endsection

@section('footer_line')
    {{ config('app.name') }} — Internal use
@endsection
