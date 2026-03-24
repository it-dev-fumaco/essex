<div style="font-family: Arial, sans-serif; font-size: 14px; color: #222; line-height: 1.6;">
    <p>Good day,</p>

    <p>
        We have a new hire joining the team. Please proceed with the standard account provisioning and hardware setup
        to ensure they are ready for their start date on <strong>{{ $data['start_date'] ?? ($data['joining_date'] ?? '') }}</strong>.
    </p>

    <h3 style="margin: 18px 0 8px 0;">Employee Details</h3>

    <p><strong>Name:</strong> {{ $data['full_name'] ?? ($data['name'] ?? '') }}</p>
    <p><strong>Position:</strong> {{ $data['job_title'] ?? ($data['role'] ?? '') }}</p>
    <p><strong>Department:</strong> {{ $data['department'] ?? '' }}</p>
    <p><strong>Reporting Manager:</strong> {{ $data['manager_name'] ?? '' }}</p>
    <p><strong>Primary Location:</strong> {{ $data['primary_location'] ?? '' }}</p>

    <h3 style="margin: 18px 0 8px 0;">Required Access &amp; Software</h3>
    <p><strong>Email &amp; Workspace:</strong> Standard Microsoft 365 / Google Workspace account.</p>
    <p><strong>ERP/Internal Systems:</strong> Standard user permissions for {{ $data['erp_system_name'] ?? 'ERP' }}.</p>
    <p><strong>Security Groups:</strong> Please add to the {{ $data['department'] ?? '' }} distribution list and shared drive.</p>
    <p><strong>VPN/Remote Access:</strong> {{ $data['vpn_remote_access'] ?? 'Yes' }}</p>

    <h3 style="margin: 18px 0 8px 0;">Hardware Requirements</h3>
    <p><strong>Workstation:</strong> Standard IT-issued laptop/desktop.</p>
    <p><strong>Peripherals:</strong> Monitor/Mouse/Keyboard/Headset.</p>
    <p><strong>Phone/Extension:</strong> {{ $data['extension'] ?? 'N/A' }}.</p>

    <h3 style="margin: 18px 0 8px 0;">Security Checklist</h3>
    <p>[ ] Enroll in Multi-Factor Authentication (MFA).</p>
    <p>[ ] Assign initial security training modules via the LMS portal.</p>
    <p>[ ] Verify workstation complies with current hardening policies.</p>

    <p style="margin-top: 18px;">Thank you.</p>
</div>

