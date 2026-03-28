<div style="font-family: Arial, sans-serif; font-size: 14px; color: #111;">
    <p>Hi HR,</p>

    <p><b>{{ $data['employee_name'] ?? 'Employee' }}</b> updated their profile details.</p>

    <p><b>Updated fields</b></p>
    <table cellpadding="6" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%; max-width: 900px;">
        <thead>
            <tr>
                <th align="left">Field</th>
                <th align="left">Old Value</th>
                <th align="left">New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach(($data['changes'] ?? []) as $change)
                <tr>
                    <td>{{ $change['label'] ?? '' }}</td>
                    <td>{{ isset($change['old']) && $change['old'] !== '' ? $change['old'] : '-' }}</td>
                    <td>{{ isset($change['new']) && $change['new'] !== '' ? $change['new'] : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 18px;">Thanks,</p>
    <p>Essex Portal</p>
</div>

