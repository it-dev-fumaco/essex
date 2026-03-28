{{--
    Email-safe layout: tables, inline styles, minimal CSS in <head> for responsive helpers.
    Tested patterns for Gmail web/mobile and Outlook (desktop web).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('title', config('app.name'))</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <style type="text/css">
        table { border-collapse: collapse; }
        td { font-family: Arial, Helvetica, sans-serif; }
    </style>
    <![endif]-->
    <style type="text/css">
        /* Progressive enhancement: ignored by clients that strip <style> */
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .email-pad { padding-left: 20px !important; padding-right: 20px !important; }
            .stack { display: block !important; width: 100% !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#eef2f7; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    @hasSection('preheader')
        <div style="display:none; font-size:1px; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden; mso-hide:all;">
            @yield('preheader')
        </div>
    @endif

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#eef2f7; border-collapse:collapse; mso-table-lspace:0; mso-table-rspace:0;">
        <tr>
            <td align="center" style="padding:24px 12px;">
                <!--[if mso]>
                <table role="presentation" align="center" width="600" cellspacing="0" cellpadding="0" border="0" style="width:600px;"><tr><td>
                <![endif]-->
                <table role="presentation" class="email-container" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:600px; width:100%; border-collapse:collapse; mso-table-lspace:0; mso-table-rspace:0;">

                    {{-- Brand header --}}
                    <tr>
                        <td style="background-color:#1e3a5f; border-radius:8px 8px 0 0; padding:20px 24px; text-align:center;">
                            @hasSection('header_logo')
                                <div style="margin-bottom:8px;">@yield('header_logo')</div>
                            @endif
                            <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:20px; font-weight:700; line-height:1.3; color:#ffffff; letter-spacing:-0.02em;">
                                @yield('brand_name', config('app.name'))
                            </p>
                            @hasSection('header_tagline')
                                <p style="margin:6px 0 0; font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:1.4; color:#cbd5e1;">
                                    @yield('header_tagline')
                                </p>
                            @endif
                        </td>
                    </tr>

                    {{-- Card body --}}
                    <tr>
                        <td class="email-pad" style="background-color:#ffffff; border-left:1px solid #e2e8f0; border-right:1px solid #e2e8f0; padding:28px 28px 32px; text-align:left;">
                            @hasSection('email_heading')
                                <h1 style="margin:0 0 16px; font-family:Arial, Helvetica, sans-serif; font-size:22px; font-weight:700; line-height:1.35; color:#0f172a; letter-spacing:-0.02em;">
                                    @yield('email_heading')
                                </h1>
                            @endif

                            <div style="font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:1.65; color:#334155;">
                                @yield('content')
                            </div>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f8fafc; border:1px solid #e2e8f0; border-top:none; border-radius:0 0 8px 8px; padding:16px 24px; text-align:center;">
                            <p style="margin:0 0 6px; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:1.5; color:#64748b;">
                                @yield('footer_line', config('app.name'))
                            </p>
                            @hasSection('footer_extra')
                                <div style="font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:1.5; color:#94a3b8;">
                                    @yield('footer_extra')
                                </div>
                            @endif
                        </td>
                    </tr>

                </table>
                <!--[if mso]>
                </td></tr></table>
                <![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
