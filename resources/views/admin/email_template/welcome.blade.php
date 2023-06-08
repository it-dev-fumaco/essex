<table border="0" width="100%" bgcolor="#f2f3f4" cellpadding="0" cellspacing="0" style="margin: 1% auto 0 auto;">
    <tr>
       <td style="padding: 3% 0 2% 0;">
          <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
             <tr>
                <td height="70" style="padding: 0 10px 5px 0;">
                   <center><img class="fix" src="https://www.fumaco.com/assets/fumlogo black.png" width="20%" border="0" alt="" /></center>
                </td>
             </tr>
          </table>
       </td>
    </tr>
 </table>
 <table bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
       <td style="padding: 3% 0 0 5%; font-weight: bold;">Hi {{ $data['name'] }},</td>
    </tr>
    <tr>
       <td style="padding: 2% 0 0 5%;">
          Welcome to FUMACO INC, and the {{ $data['department'] }} Department, we are pleased you are joining us as a {{ $data['job_title'] }}.
          <br><br>
          Please see below Guide on how to access systems and tools that you will use.
       </td>
    </tr>
    <tr>
       <td style="padding: 1% 5%;">
          <table border="1" style="width: 100%; border-collapse: collapse;">
             <thead>
                <tr style="background-color: #e5e7e9;">
                   <th style="width: 20%; padding: 0.5%; text-align: center;">System</th>
                   <th style="width: 35%; padding: 0.5%; text-align: center;">Transaction</th>
                   <th style="width: 25%; padding: 0.5%; text-align: center;">URL</th>
                   <th style="width: 25%; padding: 0.5%; text-align: center;">Login Used</th>
                </tr>
             </thead>
             <tbody>
                <tr>
                   <td style=" padding: 0.5%; text-align: center;">ERP</td>
                   <td style=" padding: 0.5%; text-align: center;">Sales Order</td>
                   <td style=" padding: 0.5%; text-align: center;"><a href="http://10.0.0.83:8000" target="_blank">http://10.0.0.83:8000</a></td>
                   <td style=" padding: 0.5%; text-align: center;">Your Windows Account/LDAP</td>
                </tr>
                <tr>
                   <td style=" padding: 0.5%; text-align: center;">AthenaERP</td>
                   <td style=" padding: 0.5%; text-align: center;">Inventory System to check Items, Stock Availability and track item movements</td>
                   <td style=" padding: 0.5%; text-align: center;"><a href="http://athenaerp.fumaco.local" target="_blank">http://athenaerp.fumaco.local</a></td>
                   <td style=" padding: 0.5%; text-align: center;">Your Windows Account/LDAP</td>
                </tr>
                <tr>
                   <td style=" padding: 0.5%; text-align: center;">Essex</td>
                   <td style=" padding: 0.5%; text-align: center;">Employee Portal, Company Updates, File your Leave of Absence, Monitor your attendance and Gatepass Form</td>
                   <td style=" padding: 0.5%; text-align: center;"><a href="http://essex.fumaco.local" target="_blank">http://essex.fumaco.local</a></td>
                   <td style=" padding: 0.5%; text-align: center;">Biometric Access ID <br> Password: <i>fumaco</i></td>
                </tr>
                <tr>
                   <td style=" padding: 0.5%; text-align: center;">Manufacturing Execution System</td>
                   <td style=" padding: 0.5%; text-align: center;">Production System for Planning and Scheduling of to be manufactured Orders</td>
                   <td style=" padding: 0.5%; text-align: center;"><a href="http://mes.fumaco.local" target="_blank">http://mes.fumaco.local</a></td>
                   <td style=" padding: 0.5%; text-align: center;">Your Windows Account/LDAP</td>
                </tr>
                <tr>
                   <td style=" padding: 0.5%; text-align: center;">Zimbra Local Email</td>
                   <td style=" padding: 0.5%; text-align: center;">Intranet Local Email (Within Company Premises Only)</td>
                   <td style=" padding: 0.5%; text-align: center;"><a href="https://zimbra.fumaco.local" target="_blank">https://zimbra.fumaco.local</a></td>
                   <td style=" padding: 0.5%; text-align: center;">Your Windows Account/LDAP</td>
                </tr>
                <tr>
                   <td style=" padding: 0.5%; text-align: center;">Outlook .com Web Mail</td>
                   <td style=" padding: 0.5%; text-align: center;">Public Email</td>
                   <td style=" padding: 0.5%; text-align: center;"><a href="https://outlook.office.com" target="_blank">https://outlook.office.com</a></td>
                   <td style=" padding: 0.5%; text-align: center;"></td>
                </tr>
             </tbody>
          </table>
       </td>
    </tr>
    <tr>
       <td style="padding: 2% 0 2% 5%;">
          <p><b>FILE SERVER</b></p>
          <p>To access your personal folder in file server</p>
          <p>\\{{ $data['file_server'] }}</p>
          <p>If you need further assistance, you may email at <a href="mailto:it@fumaco.local">it@fumaco.local</a> or call us at local 3201</p>
          <br>
          Best Regards, <br>
          IT Team
       </td>
    </tr>
 </table>
 <table border="0" width="100%" bgcolor="#f2f3f4" cellpadding="0" cellspacing="0" style="margin: 0 auto 0 auto;">
    <tr>
       <td align="center" style="padding: 3%;">
          <a href="https://facebook.com/fumaco.inc" target="_blank"><img src="https://www.fumaco.com/assets/facebook-square-brands.svg" width="25" height="25" style="margin: 5px 5px 0 5px;"></a>
          <a href="https://www.instagram.com/thisisfumaco" target="_blank"><img src="https://www.fumaco.com/assets/instagram-brands.svg" width="25" height="25" style="margin: 5px 5px 0 5px;"></a>
          <p style="margin: 0;">
             <a href="https://www.fumaco.com" target="_blank">www.fumaco.com</a>
          </p>
          <br><br>				
          &copy; Copyright 2021 Fumaco. All rights reserved. Fumaco, the Fumaco logo and other Fumaco<br>marks are owned by Fumaco and may be registered. All other trademarks are the property of their<br>respective owners.
          <br><br>
          <span style="font-size: 14px;">Fumaco</span><br>35 Pleasant View Drive, Bagbaguin, Caloocan City, Metro Manila, Philippines
       </td>
    </tr>
 </table>