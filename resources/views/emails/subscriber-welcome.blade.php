<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome to Invest Lagos Updates</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f4;padding:32px 16px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

          {{-- Header --}}
          <tr>
            <td style="background:#1B3A2D;padding:28px 40px;text-align:left;">
              <p style="margin:0;color:#ffffff;font-size:18px;font-weight:700;letter-spacing:0.5px;">
                Invest Lagos
              </p>
            </td>
          </tr>

          {{-- Body --}}
          <tr>
            <td style="padding:40px 40px 32px;color:#1a1a1a;font-size:15px;line-height:1.7;">
              <p style="margin:0 0 20px;">
                Dear <strong>{{ $subscriber->first_name }} {{ $subscriber->last_name }}</strong>,
              </p>
              <p style="margin:0 0 20px;">
                Thank you for subscribing to <strong>Invest Lagos</strong>. You will now receive
                exclusive updates, investor insights, partnership opportunities and notifications
                whenever we release new publications.
              </p>
              <p style="margin:0 0 20px;">
                Should you have any questions, please reach us at
                <a href="mailto:investinlagos@lagosmccti.org" style="color:#3EBF75;text-decoration:none;">investinlagos@lagosmccti.org</a>
                or visit
                <a href="https://investlagos.org" style="color:#3EBF75;text-decoration:none;">investlagos.org</a>.
              </p>
              <p style="margin:0 0 8px;">Warm regards,</p>
              <p style="margin:0;font-weight:600;color:#1B3A2D;">The Invest Lagos Team</p>
            </td>
          </tr>

          {{-- Divider --}}
          <tr>
            <td style="padding:0 40px;">
              <hr style="border:none;border-top:1px solid #e8e8e8;margin:0;" />
            </td>
          </tr>

          {{-- Footer --}}
          <tr>
            <td style="padding:24px 40px;text-align:center;font-size:12px;color:#888888;line-height:1.6;">
              <p style="margin:0 0 6px;">&copy; {{ date('Y') }} Invest Lagos. All rights reserved.</p>
              <p style="margin:0;">Lagos Ministry of Commerce, Cooperatives, Trade &amp; Investment (MCCTI)</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
