@php
    $params = '?update_from_mail=1';
    $params .= '&notice_id='.$data['slip_id'];
    $params .= '&approver_id='.$data['approver'];
    $params .= '&token='.$data['token'];
@endphp
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12" style="margin-left:10%;margin-right:10%;">
        <h3><b>{{ $data['employee_name'] }} filed an absent notice slip request for your approval.</b></h3>
        <br>
        <div style="display:block; line-height:8px;">
            <p>Department: <b> {{ $data['department'] }}</b></p>
            <p>Type of Absence: <b> {{ $data['leave_type']  }}</b></p>
            <p>Absent Notice Slip no. <b> {{ $data['slip_id'] }}</b></p>
            <p>Reported to: <b>{{ $data['reported_to'] }} </b></p>
            <p>Report made through <b> {{ $data['means']  }}</b></p>
            <p>From: <b> {{ $data['from']  }}</b> To:<b> {{ $data['to']  }}</b></p>
            <p>Reason: <b> {{ $data['reason'] }}</b></p>
        </div>
        <br>
        <a href="{{ env('APP_URL', 'https://essex.fumaco.local') }}/notice_slip/updateStatus{{ $params }}&approved=1" class="btn btn-success">Approve</a>
        <a href="{{ env('APP_URL', 'https://essex.fumaco.local') }}/notice_slip/updateStatus{{ $params }}" class="btn btn-danger">Disapprove</a>
        <br>
        <hr>
        <p>Or log in to https://essex.fumaco.local to Approved or Cancel Request</p><br><b>Fumaco Inc / Absent Notice Slip {{ $data['year'] }} </b>
        <br></br>
        <small>Auto Generated E-mail from Essex - NO REPLY </small>
        </div>
    </div>
</div>
<style>
    .btn{
        padding: 5px 10px 5px 10px;
        color: #fff !important;
        font-weight: 600;
        text-transform: none !important;
        text-decoration: none !important;
        display: inline-block;
        margin: 5px;
    }

    .btn-danger{
        background-color: red;
    }

    .btn-success{
        background-color: #4CAF50;
    }
</style>