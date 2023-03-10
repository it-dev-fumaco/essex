<table border="0" width="100%" bgcolor="#f2f3f4" cellpadding="0" cellspacing="0" style="margin: 1% auto 0 auto;">
    <tr>
       <td style="padding: 3% 0 2% 0;">
          <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
             <tr>
                <td height="70" style="padding: 0 10px 5px 0;">
                   <center><img class="fix" src="{{ asset('storage/work_anniv.jpg') }}" width="40%" border="0" alt="" /></center>
                </td>
             </tr>
          </table>
       </td>
    </tr>
 </table>
 <table bgcolor="#ffffff" style="margin: 0 auto 0 auto;">
    <tr>
        <td style="padding: 3% 0 0 5%; font-weight: bold;">Happy Work Anniversary {{ $data['name'] }}!</td>
    </tr>
    <tr>
        <td style="padding: 2% 0 0 5%;">
            <br>
            We are grateful for your contribution and dedication to our company.
            <br><br><br>
            Today, you complete {{ $data['no_of_years'] > 1 ? $data['no_of_years'].' years' : $data['no_of_years'].' year' }} with us.
            <br><br><br>
            Wishing you a happy work anniversary.
            <br><br><br>
            All the Best,<br>
            IT Team
            <br>
       </td>
    </tr>
 </table>