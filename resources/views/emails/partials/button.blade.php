{{--
    Bulletproof button: table + anchor, inline styles. Pass $url, $label; optional $align (left|center|right), $fullWidth (bool)
--}}
@php
    $align = $align ?? 'left';
    $fullWidth = $fullWidth ?? false;
    $marginBottom = $marginBottom ?? '16px';
    $tdAlign = $align === 'center' ? 'center' : ($align === 'right' ? 'right' : 'left');
    $tableStyle = $fullWidth ? 'width:100%;' : '';
@endphp
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 {{ $marginBottom }}; {{ $tableStyle }}">
    <tr>
        <td align="{{ $tdAlign }}" style="padding:0;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="border-collapse:separate;">
                <tr>
                    <td align="center" bgcolor="#2563eb" style="background-color:#2563eb; border-radius:6px; mso-padding-alt:12px 22px;">
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" style="display:inline-block; padding:12px 22px; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:600; line-height:1.4; color:#ffffff; text-decoration:none; border-radius:6px; mso-line-height-rule:exactly;">
                            {{ $label }}
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
