<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/google_fonts/fonts.css" rel="stylesheet">
    <title>KOREK - Fleet MS</title>
    <style>
        @media (max-width: 500px) {
            img {
                width: 300px;
            }
        }
        table #data{
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #data td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        #data tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        table, td, div, h1, p {font-family: Arial, sans-serif;}
    </style>
</head>
<body style="margin:0;padding:0;">
<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
        <td align="center" style="padding:0;">
            <table role="presentation" style="width:80%;border-collapse:collapse;border:0px #cccccc;border-spacing:0;text-align:left;">
                <tr>
                    <td align="center" style="padding:20px 0 15px 0;background:#ffffff;">
                        <img src="cid:my-attach" alt="" width="30%" style="height:auto;display:block;" />
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                                <td style="padding:0 0 36px 0;color:#153643;">
                                    <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">{{$message}}</h1>
{{--                                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Follow the below link to see more detail</p>--}}
{{--                                    <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="{{$link}}" style="color:#ee4c50;text-decoration:underline;">Open request detail</a></p>--}}
{{--                                    {!! $table !!}--}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Please don't reply to this email</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px;background:#0066cc;">
                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                            <tr>
                                <td style="padding:0;width:50%;" align="left">
                                    <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                        &copy; <a href="http://www.korektel.com" style="color:#ffffff;text-decoration:underline;">Korek Telecom</a> {{date('Y')}}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
