<div style="font-family: Arial, sans-serif; font-size: 14px; color: #222; line-height: 1.6;">
    <p>Good day,</p>

    <p>
        Please initiate the standard offboarding procedure for the employee listed below. To maintain our security posture
        and infrastructure integrity, ensure all account deactivations and hardware retrievals are completed by
        <strong>{{ $data['last_working_date'] ?? ($data['last_day'] ?? '') }}</strong>.
    </p>

    <h3 style="margin: 18px 0 8px 0;">Employee Details</h3>

    <p><strong>Name:</strong> {{ $data['full_name'] ?? ($data['name'] ?? '') }}</p>
    <p><strong>Position:</strong> {{ $data['job_title'] ?? ($data['role'] ?? '') }}</p>
    <p><strong>Department:</strong> {{ $data['department'] ?? '' }}</p>
    <p><strong>Primary Location:</strong> {{ $data['primary_location'] ?? '' }}</p>

    <h3 style="margin: 18px 0 8px 0;">Access Revocation &amp; Deactivation</h3>
    <p><strong>Email:</strong> Disable Microsoft 365 account and set an auto-reply (if requested by the Manager).</p>
    <p><strong>ERPNext:</strong> Deactivate user account and revoke all module permissions.</p>
    <p><strong>AthenaERP / MES / Essex:</strong> Terminate all active sessions and disable login access.</p>
    <p><strong>VPN/Remote Access:</strong> Remove the user from VPN Access</p>
    <p><strong>DL / Groups:</strong> Remove the user from all Distribution Lists and shared drive permissions.</p>

    <h3 style="margin: 18px 0 8px 0;">Hardware &amp; Asset Recovery</h3>
    <p><strong>Workstation:</strong> Retrieve laptop/desktop and peripherals.</p>
    <p><strong>Mobile/Other:</strong> Company phone, tokens, or RSA keys (if issued).</p>
    <p><strong>Inspection:</strong> Verify the returned hardware for any physical damage or unauthorized software.</p>

    <h3 style="margin: 18px 0 8px 0;">Security Checklist</h3>
    <p>[ ] MFA: Disable Multi-Factor Authentication for the user.</p>
    <p>[ ] LMS: Deactivate the user profile on the LMS portal.</p>
    <p>[ ] Data Backup: Ensure any critical local data is backed up to the department's shared drive before wiping the device.</p>

    <p style="margin-top: 18px;">Thank you.</p>
</div>
