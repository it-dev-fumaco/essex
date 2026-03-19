<div style="font-family: Arial, sans-serif; font-size: 14px; color: #222;">
    <p>Good day,</p>

    <p>Your Absent Notice has been updated with the following details:</p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 720px;">
        <tr>
            <td style="border: 1px solid #ddd; width: 220px;"><strong>Employee Name</strong></td>
            <td style="border: 1px solid #ddd;">{{ $data['employee_name'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Type of Absence</strong></td>
            <td style="border: 1px solid #ddd;">{{ $data['type_of_absence'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Date Range (From – To)</strong></td>
            <td style="border: 1px solid #ddd;">{{ $data['date_range'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Reason</strong></td>
            <td style="border: 1px solid #ddd;">{{ $data['reason'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Approval Status</strong></td>
            <td style="border: 1px solid #ddd;">{{ $data['approval_status'] ?? '' }}</td>
        </tr>
    </table>

    <p style="margin-top: 18px;">Thank you.</p>
</div>

