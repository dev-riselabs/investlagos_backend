<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $publication->title }}</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f4f4;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
    @php
        $siteUrl = rtrim(config('app.frontend_url', 'https://investlagos.org'), '/');
        $publicationUrl = $publication->external_url ?: $siteUrl . '/pressroom/publications/' . $publication->slug;
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f4;padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#1B3A2D;padding:28px 40px;text-align:left;">
                            <img src="{{ $message->embed(public_path('logo.png')) }}" alt="Invest Lagos" width="180"
                                style="height:auto;display:block;margin:0 auto;max-width:180px;" />
                            <p style="margin:0;color:#ffffff;font-size:18px;font-weight:700;letter-spacing:0.5px;">
                                &middot; New Publication
                            </p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px 40px 16px;color:#1a1a1a;font-size:15px;line-height:1.7;">
                            <p style="margin:0 0 20px;">
                                Dear <strong>{{ $subscriber->first_name }}</strong>,
                            </p>
                            <p style="margin:0 0 20px;">
                                A new publication has just been released on Invest Lagos:
                            </p>

                            <h2 style="margin:0 0 8px;font-size:20px;color:#1B3A2D;">
                                {{ $publication->title }}
                            </h2>
                            @if ($publication->category)
                                <p
                                    style="margin:0 0 16px;font-size:12px;text-transform:uppercase;letter-spacing:1px;color:#3EBF75;font-weight:700;">
                                    {{ $publication->category }} &middot; {{ $publication->year }}
                                </p>
                            @endif

                            <p style="margin:0 0 24px;color:#444;">
                                {{ \Illuminate\Support\Str::limit($publication->description, 280) }}
                            </p>

                            <p style="margin:0 0 28px;">
                                <a href="{{ $publicationUrl }}"
                                    style="display:inline-block;background:#3EBF75;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:6px;font-weight:700;font-size:14px;">
                                    Read Publication
                                </a>
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
                            <p style="margin:0 0 6px;">
                                You are receiving this email because you subscribed to Invest Lagos updates at
                                <strong>{{ $subscriber->email }}</strong>.
                            </p>
                            <p style="margin:0;">&copy; {{ date('Y') }} Invest Lagos. All rights reserved.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
