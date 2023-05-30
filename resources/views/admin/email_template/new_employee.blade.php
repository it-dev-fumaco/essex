<div style="width: 100%; padding: 20px;">
   <h4>A new employee has been added in Essex!</h4>
   Please see the following details:
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
         <td style="text-align: left;">Birthday</td>
         <th style="text-align: left; padding: 5px 5px 5px 0">&nbsp;:&nbsp;{{ Carbon\Carbon::parse($data['birthday'])->format('F d, Y') }}</th>
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
   </table>
   <br>
   <p>Please create the accounts for the following:
      <ul>
         <li>AthenaERP Inventory</li>
         <li>Enterprise Resource Planning</li>
         <li>Manufacturing Execution System</li>
         <li>Zimbra</li>
      </ul>
   </p>
   <p>Thank you!</p>
</div>