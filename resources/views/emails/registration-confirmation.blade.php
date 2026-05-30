<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration Confirmed – Invest Lagos 3.0</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f4;padding:32px 16px;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

          {{-- Header with logo (base64 inline so it shows in all email clients) --}}
          <tr>
            <td style="background-color:#1B3A2D;padding:28px 40px;text-align:center;">
              <img
                src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo_2.png'))) }}"
                alt="Invest Lagos"
                width="180"
                style="height:auto;display:block;margin:0 auto;max-width:180px;"
              />
            </td>
          </tr>

          {{-- Green accent bar --}}
          <tr>
            <td style="background-color:#3EBF75;height:4px;font-size:0;line-height:0;">&nbsp;</td>
          </tr>

          {{-- Body --}}
          <tr>
            <td style="padding:40px 40px 32px;color:#1a1a1a;font-size:15px;line-height:1.7;">
              <p style="margin:0 0 20px;">Greetings.</p>
              <p style="margin:0 0 20px;">
                Dear <strong>{{ $registration->title }} {{ $registration->first_name }} {{ $registration->last_name }}</strong>,
              </p>
              <p style="margin:0 0 20px;">
                Thank you for registering to attend and participate in <strong>Invest in Lagos 3.0</strong>.
              </p>
              <p style="margin:0 0 20px;">
                We have successfully received your application, which is currently undergoing review by our team.
                Once the review process is complete, you will receive a confirmation email with further details
                regarding your participation.
              </p>
              <p style="margin:0 0 20px;">
                Should you have any questions or require additional information, please contact us at
                <a href="mailto:investinlagos@lagosmccti.org" style="color:#3EBF75;text-decoration:none;">investinlagos@lagosmccti.org</a>
                or visit the Invest in Lagos website at
                <a href="https://investlagos.org" style="color:#3EBF75;text-decoration:none;">investlagos.org</a>.
              </p>
              <p style="margin:0 0 20px;">
                We appreciate your interest in joining us and look forward to welcoming you to
                <strong>Invest in Lagos, the Business Gateway to Africa</strong>.
              </p>
              <p style="margin:0 0 8px;">Please have our very best wishes.</p>
              <p style="margin:0;font-weight:600;color:#1B3A2D;">The Administrative Secretariat of Invest Lagos 3.0.</p>
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
