<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f7fa; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);">

                    <tr>
                        <td align="center" style="padding: 40px 20px 20px 20px;">
                            <img src="https://raw.githubusercontent.com/Gungcakra/random-repo/refs/heads/main/logo_telerehab.jpeg" alt="Email Icon" style="height: 64px; width: auto; display: block; margin: 0 auto;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 40px 40px 40px; text-align: center;">
                            <h1 style="margin: 0 0 16px 0; color: #1e293b; font-size: 28px; font-weight: 800; tracking: -0.5px;">Confirm your email</h1>

                            <p style="margin: 0 0 32px 0; color: #64748b; font-size: 16px; line-height: 1.6;">
                                Hi <strong>{{ $name }}</strong>,<br>
                                Welcome to our rehabilitation platform.
                                Please confirm your email address to activate your account and begin your personalized recovery journey.
                            </p>

                            <table border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                                <tr>
                                    <td align="center" style="border-radius: 14px;" bgcolor="#14b8a6">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: sans-serif; font-size: 16px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 14px; background-color: #14b8a6; border: 1px solid #14b8a6;">
                                            Verify Account
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 32px 0 0 0; color: #94a3b8; font-size: 14px;">
                                If you didn't create an account, you can safely ignore this message.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px 40px; background-color: #f8fafc; text-align: center; border-top: 1px solid #f1f5f9;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.5; text-transform: uppercase; letter-spacing: 1px;">
                                &copy; {{ date('Y') }} {{ config('app.name') }} Inc.
                            </p>
                            <p style="margin: 8px 0 0 0; color: #cbd5e1; font-size: 12px;">
                                Jakarta, Indonesia
                            </p>
                        </td>
                    </tr>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin-top: 24px;">
                    <tr>
                        <td style="text-align: center; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                            Button not working? Copy and paste this link into your browser:<br>
                            <a href="{{ $url }}" style="color: #14b8a6; text-decoration: none; word-break: break-all;">{{ $url }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>