<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Schedule</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f7fa; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f4f7fa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);">

                    <tr>
                        <td align="center" style="padding: 40px 20px 20px 20px;">
                            <img src="https://raw.githubusercontent.com/Gungcakra/random-repo/refs/heads/main/logo_telerehab.jpeg" alt="Logo" style="height: 64px; width: auto; display: block; margin: 0 auto;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 40px 40px 40px; text-align: center;">
                            <h1 style="margin: 0 0 16px 0; color: #1e293b; font-size: 28px; font-weight: 800; letter-spacing: -0.5px;">Consultation Scheduled</h1>

                            <p style="margin: 0 0 32px 0; color: #64748b; font-size: 16px; line-height: 1.6;">
                                Hi <strong>{{ $scheduleDetails['name'] }}</strong>,<br>
                                Your consultation has been scheduled. Please find the details below.
                            </p>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border-radius: 12px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px; text-align: left;">
                                        <p style="margin: 0 0 12px 0; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>Doctor</strong></p>
                                        <p style="margin: 0 0 16px 0; color: #1e293b; font-size: 16px; font-weight: 600;">{{ $scheduleDetails['doctor'] }}</p>

                                        <p style="margin: 0 0 12px 0; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>Date & Time</strong></p>
                                        <p style="margin: 0 0 16px 0; color: #1e293b; font-size: 16px; font-weight: 600;">{{ \Carbon\Carbon::parse($scheduleDetails['date'])->format('j F Y') }} at {{ $scheduleDetails['time'] }}</p>

                                        <p style="margin: 0 0 12px 0; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>Location</strong></p>
                                        <p style="margin: 0; color: #1e293b; font-size: 16px; font-weight: 600;">{{ $scheduleDetails['location'] }}</p>
                                    </td>
                                </tr>
                            </table>


                            <p style="margin: 32px 0 0 0; color: #94a3b8; font-size: 14px;">
                                Please arrive 5 minutes early. If you need to reschedule, contact your doctor.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px 40px; background-color: #f8fafc; text-align: center; border-top: 1px solid #f1f5f9;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.5; text-transform: uppercase; letter-spacing: 1px;">
                                &copy; {{ date('Y') }} {{ config('app.name') }} Inc.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>