<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $catalogue->title }}</title>
</head>
<body style="margin: 0; background: #f8fafc; color: #0f172a; font-family: Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: #f8fafc; padding: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 600px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td style="background: #047857; padding: 20px 24px; color: #ffffff;">
                            <div style="font-size: 14px; font-weight: 700;">New special from {{ $shop->name }}</div>
                            <h1 style="margin: 8px 0 0; font-size: 24px; line-height: 1.25;">{{ $catalogue->title }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px;">
                            @if($catalogue->category)
                                <p style="margin: 0 0 12px; color: #047857; font-size: 13px; font-weight: 700;">{{ $catalogue->category->name }}</p>
                            @endif

                            <p style="margin: 0 0 16px; color: #475569; font-size: 15px; line-height: 1.6;">
                                {{ $catalogue->description ?: 'A new catalogue special is now available.' }}
                            </p>

                            <p style="margin: 0 0 20px; color: #334155; font-size: 14px;">
                                Valid from <strong>{{ $catalogue->valid_from->format('d M Y') }}</strong>
                                to <strong>{{ $catalogue->valid_to->format('d M Y') }}</strong>.
                            </p>

                            <a href="{{ $catalogueUrl }}" style="display: inline-block; background: #047857; color: #ffffff; padding: 12px 16px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 700;">View catalogue</a>

                            <p style="margin: 24px 0 0; color: #64748b; font-size: 12px; line-height: 1.5;">
                                You are receiving this because you asked to receive specials by email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
