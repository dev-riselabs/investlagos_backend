<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Investment Project Proposal Received – Invest Lagos 3.0</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f4;padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

                    <tr>
                        <td
                            style="background-color:#ffffff;padding:28px 40px;text-align:center;border-bottom:3px solid #1B3A2D;">
                            <img src="{{ $message->embed(public_path('logo.png')) }}" alt="Invest Lagos" width="180"
                                style="height:auto;display:block;margin:0 auto;max-width:180px;" />
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color:#3EBF75;height:4px;font-size:0;line-height:0;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="padding:40px 40px 32px;color:#1a1a1a;font-size:15px;line-height:1.7;">
                            <p style="margin:0 0 20px;"><strong>Your Invest Lagos 3.0 Investment Project Proposal has been received.</strong></p>
                            <p style="margin:0 0 20px;">
                                Greetings <strong>{{ $proposal->project_owner }}</strong>,
                            </p>
                            <p style="margin:0 0 20px;">
                                Thank you for submitting <strong>&ldquo;{{ $proposal->project_title }}&rdquo;</strong>
                                to the <strong>Invest in Lagos 3.0</strong> Deal Room.
                            </p>
                            <p style="margin:0 0 20px;">
                                Your proposal is now in our review pipeline. Our investment evaluation team will
                                assess the project against the summit's deal-room criteria and reach out with
                                further details on the next steps, typically within 7&ndash;14 working days.
                            </p>

                            <table cellpadding="0" cellspacing="0" border="0"
                                style="width:100%;background:#f7faf8;border:1px solid #e3ece7;border-radius:6px;padding:18px 20px;margin:0 0 20px;">
                                <tr><td style="padding:4px 0;color:#1B3A2D;"><strong>Project:</strong> {{ $proposal->project_title }}</td></tr>
                                <tr><td style="padding:4px 0;color:#1B3A2D;"><strong>Sector:</strong> {{ $proposal->sector }}</td></tr>
                                <tr><td style="padding:4px 0;color:#1B3A2D;"><strong>Location:</strong> {{ $proposal->project_location }}</td></tr>
                                <tr><td style="padding:4px 0;color:#1B3A2D;"><strong>Organization:</strong> {{ $proposal->organization }}</td></tr>
                                @if(!is_null($proposal->investment_estimate_usd))
                                <tr><td style="padding:4px 0;color:#1B3A2D;"><strong>Project Value (USD):</strong> ${{ number_format((float) $proposal->investment_estimate_usd, 2) }}</td></tr>
                                @endif
                                <tr><td style="padding:4px 0;color:#1B3A2D;"><strong>Reference ID:</strong> ILP-{{ str_pad($proposal->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
                            </table>

                            <p style="margin:0 0 20px;">
                                Should you have any questions or wish to share additional supporting material,
                                please reply to this email or reach us at
                                <a href="mailto:investinlagos@lagosmccti.org"
                                    style="color:#3EBF75;text-decoration:none;">investinlagos@lagosmccti.org</a>
                                or
                                <a href="tel:+2347076623338"
                                    style="color:#3EBF75;text-decoration:none;">+234.707.662.3338</a>.
                            </p>
                            <p style="margin:0 0 20px;">
                                We appreciate your interest in advancing Lagos&rsquo; investment landscape and look
                                forward to engaging with you on this opportunity.
                            </p>
                            <p style="margin:0 0 8px;"><strong>Warmly,</strong></p>
                            <p style="margin:0;font-weight:600;color:#1B3A2D;">The Deal Room Secretariat &mdash; Invest Lagos 3.0.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 40px;">
                            <hr style="border:none;border-top:1px solid #e8e8e8;margin:0;" />
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 40px;text-align:center;font-size:12px;color:#888888;line-height:1.6;">
                            <p style="margin:0 0 6px;">
                                &copy; {{ date('Y') }} Invest Lagos. All rights reserved.
                            </p>
                            <p style="margin:0;">
                                Lagos Ministry of Commerce, Cooperatives, Trade &amp; Investment (MCCTI)
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
