@extends('emails.layouts.master')

@section('title', 'Absent notice update — ' . config('app.name'))

@section('preheader')
    Your absent notice was updated: {{ $data['approval_status'] ?? '' }} — {{ $data['employee_name'] ?? '' }}.
@endsection

@section('email_heading')
    Absent Notice - {{ $data['approval_status'] ?? '' }}
@endsection

@section('content')
    <p style="margin:0 0 16px;">Good day,</p>

    <p style="margin:0 0 20px;">Your absent notice has been <b>{{ $data['approval_status'] ?? '' }}</b>, see the details below:</p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse; border:1px solid #e2e8f0; border-radius:6px; overflow:hidden;">
        <tr>
            <td style="padding:12px 14px; background-color:#f8fafc; border-bottom:1px solid #e2e8f0; width:38%; font-size:13px; font-weight:700; color:#475569;">Employee name</td>
            <td style="padding:12px 14px; border-bottom:1px solid #e2e8f0; font-size:14px; color:#0f172a;">{{ $data['employee_name'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px; background-color:#ffffff; border-bottom:1px solid #e2e8f0; font-size:13px; font-weight:700; color:#475569;">Type of absence</td>
            <td style="padding:12px 14px; border-bottom:1px solid #e2e8f0; font-size:14px; color:#0f172a;">{{ $data['type_of_absence'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="padding:12px 14px; background-color:#f8fafc; border-bottom:1px solid #e2e8f0; font-size:13px; font-weight:700; color:#475569;">Date range (from – to)</td>
            <td style="padding:12px 14px; border-bottom:1px solid #e2e8f0; font-size:14px; color:#0f172a;">{{ $data['date_range'] ?? '' }}</td>
        </tr>
    </table>

    <p style="margin:24px 0 0;">Thank you.</p>
@endsection

@section('footer_line')
    {{ config('app.name') }}
@endsection
