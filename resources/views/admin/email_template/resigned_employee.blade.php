<div style="width: 100%; padding: 20px;">
    <h4>An employee has resigned</h4>
    Please see the following details:
    <br>
    <table style="margin-top: 10px;">
        <tr>
           <td style="text-align: left;">Employee ID</td>
           <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ $data['employee_id'] }}</th>
        </tr>
        <tr>
           <td style="text-align: left;">Access ID</td>
           <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ $data['biometric_id'] }}</th>
        </tr>
        <tr>
           <td style="text-align: left;">Name</td>
           <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ $data['name'] }}</th>
        </tr>
        <tr>
           <td style="text-align: left;">Department</td>
           <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ $data['department'] }}</th>
        </tr>
        <tr>
           <td style="text-align: left;">Designation</td>
           <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ $data['designation'] }}</th>
        </tr>
        <tr>
           <td style="text-align: left;">Reporting to</td>
           <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ $data['reporting_to'] }}</th>
        </tr>
        <tr>
           <td style="text-align: left;">Branch</td>
           <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ $data['location'] }}</th>
        </tr>
        <tr>
            <td style="text-align: left;">Resignation Date</td>
            <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ Carbon\Carbon::parse($data['resignation_date'])->format('M d, Y') }}</th>
         </tr>
     </table>
    <br>
    <p>Please disable and/or remove the accounts for the following:
    <ul>
        <li>AthenaERP Inventory</li>
        <li>Enterprise Resource Planning</li>
        <li>Manufacturing Execution System</li>
        <li>Zimbra</li>
    </ul>
    </p>
    <p>Thank you!</p>
</div>
{{-- <table bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
        <td style="padding: 3% 0 0 5%; font-weight: bold;">An employee has resigned</td>
    </tr>
    <tr>
        <td style="padding: 2% 0 0 5%;">
            <br>
            <p>Please disable the accounts for the following:
            <ul>
                <li>AthenaERP Inventory</li>
                <li>Enterprise Resource Planning</li>
                <li>Manufacturing Execution System</li>
                <li>Zimbra</li>
            </ul>
            </p>
            <br>
        </td>
    </tr>
</table> --}}
{{--
Dear [Company Name] staff, I am writing today to notify you that [Employee Name] is departing the company, effective
[leave date]. [Employee Name] has decided to [reason for leaving]. As of [leave date], please direct all department
questions to [Interim Employee] until we are able to secure a replacement. --}}