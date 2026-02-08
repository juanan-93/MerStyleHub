<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $notification->title }} - MerStyleHub</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }
        
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .fluid { max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important; }
            .stack-column, .stack-column-center { display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important; }
            .mobile-padding { padding-left: 20px !important; padding-right: 20px !important; }
            .mobile-padding-top { padding-top: 30px !important; }
            .mobile-padding-bottom { padding-bottom: 30px !important; }
            .mobile-center { text-align: center !important; }
            .mobile-font-large { font-size: 22px !important; line-height: 28px !important; }
            .mobile-font-medium { font-size: 16px !important; line-height: 24px !important; }
            .mobile-font-small { font-size: 13px !important; line-height: 20px !important; }
            .mobile-button { width: 100% !important; max-width: 300px !important; }
        }
        
        @media only screen and (max-width: 480px) {
            .mobile-padding { padding-left: 15px !important; padding-right: 15px !important; }
            .mobile-font-large { font-size: 20px !important; line-height: 26px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #ECE9E2; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <!-- Preheader Text -->
    <div style="display: none; font-size: 1px; color: #ECE9E2; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        {{ $notification->message }}
    </div>
    
    <!-- Email Wrapper -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #ECE9E2;">
        <tr>
            <td align="center" style="padding: 20px 10px 40px 10px;">
                
                <!-- Email Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" class="email-container" style="max-width: 600px; width: 100%;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 30px 20px 20px 20px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 28px; font-weight: 600; color: #343434; letter-spacing: 1px;">
                                        MerStyleHub
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Main Card -->
                    <tr>
                        <td class="mobile-padding" style="padding: 0 20px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                                
                                <!-- Emoji Icon -->
                                <tr>
                                    <td align="center" class="mobile-padding-top" style="padding: 40px 30px 15px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td align="center" style="width: 70px; height: 70px; background-color: {{ $bgColor }}; border-radius: 50%; border: 2px solid {{ $accentColor }}20;">
                                                    <span style="font-size: 32px; line-height: 70px;">{{ $emoji }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <!-- Title -->
                                <tr>
                                    <td align="center" style="padding: 10px 30px 5px 30px;">
                                        <h1 class="mobile-font-large" style="margin: 0; font-family: 'Georgia', 'Times New Roman', serif; font-size: 24px; font-weight: 600; color: #343434; line-height: 32px;">
                                            {{ $notification->title }}
                                        </h1>
                                    </td>
                                </tr>
                                
                                <!-- Greeting -->
                                <tr>
                                    <td align="center" style="padding: 5px 30px 20px 30px;">
                                        <p class="mobile-font-medium" style="margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; color: #6C757D; line-height: 22px;">
                                            Hola <strong style="color: #343434;">{{ $user->name }}</strong>
                                        </p>
                                    </td>
                                </tr>
                                
                                <!-- Divider -->
                                <tr>
                                    <td style="padding: 0 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="border-top: 1px solid #E9E6E0;"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <!-- Message Content -->
                                <tr>
                                    <td class="mobile-padding" style="padding: 25px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: {{ $bgColor }}; border-radius: 12px; border-left: 4px solid {{ $accentColor }};">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <p class="mobile-font-medium" style="margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; color: #343434; line-height: 24px;">
                                                        {{ $notification->message }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Detalles extra según tipo --}}
                                @if($notification->type === \App\Models\Notification::TYPE_APPOINTMENT_MOVED && !empty($notification->data))
                                <tr>
                                    <td class="mobile-padding" style="padding: 0 30px 20px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #F8F6F3; border-radius: 12px;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="padding-bottom: 12px;">
                                                                <span style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; font-weight: 600; color: #A08A7A; text-transform: uppercase; letter-spacing: 1px;">
                                                                    Nueva fecha y hora
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    @if(!empty($notification->data['new_date']))
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 8px; text-align: center; vertical-align: middle;">
                                                                <span style="font-size: 18px; line-height: 36px;">📅</span>
                                                            </td>
                                                            <td style="padding-left: 12px; padding-bottom: 10px; vertical-align: middle;">
                                                                <span style="display: block; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; color: #6C757D;">Fecha</span>
                                                                <span style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; font-weight: 600; color: #343434;">{{ $notification->data['new_date'] }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    @endif
                                                    @if(!empty($notification->data['new_time']))
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 8px; text-align: center; vertical-align: middle;">
                                                                <span style="font-size: 18px; line-height: 36px;">🕐</span>
                                                            </td>
                                                            <td style="padding-left: 12px; vertical-align: middle;">
                                                                <span style="display: block; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; color: #6C757D;">Horario</span>
                                                                <span style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; font-weight: 600; color: #343434;">{{ $notification->data['new_time'] }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif

                                {{-- Botón de acción --}}
                                @if($notification->action_url)
                                <tr>
                                    <td align="center" class="mobile-padding" style="padding: 5px 30px 15px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td class="mobile-button" style="border-radius: 10px; background-color: {{ $accentColor }};">
                                                    <a href="{{ $notification->action_url }}" target="_blank" style="display: inline-block; padding: 14px 32px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; font-weight: 600; color: #FFFFFF; text-decoration: none; border-radius: 10px;">
                                                        Ver en la plataforma
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif

                                <!-- Nota informativa -->
                                <tr>
                                    <td class="mobile-padding" style="padding: 10px 30px 20px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #FFF9E6; border-radius: 10px; border: 1px solid #FFE69C;">
                                            <tr>
                                                <td style="padding: 14px 16px;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="width: 24px; vertical-align: top; padding-top: 2px;">
                                                                <span style="font-size: 16px;">💡</span>
                                                            </td>
                                                            <td style="padding-left: 10px;">
                                                                <span class="mobile-font-small" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #856404; line-height: 19px;">
                                                                    También puedes ver esta notificación y todas las anteriores desde tu panel en la plataforma.
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Spacer -->
                                <tr>
                                    <td style="padding-bottom: 15px;"></td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 30px 20px 20px 20px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 15px;">
                                        <span style="font-family: 'Georgia', 'Times New Roman', serif; font-size: 20px; font-weight: 600; color: #343434;">
                                            MerStyleHub
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom: 20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                        <span style="font-size: 16px;">📷</span>
                                                    </a>
                                                </td>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                        <span style="font-size: 16px;">📘</span>
                                                    </a>
                                                </td>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 50%; text-align: center; line-height: 36px; text-decoration: none;">
                                                        <span style="font-size: 16px;">💬</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <p class="mobile-font-small" style="margin: 0 0 8px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #6C757D; line-height: 20px;">
                                            ¿Tienes alguna pregunta? Escríbenos a<br>
                                            <a href="mailto:info@merstylehub.com" style="color: #A08A7A; text-decoration: none; font-weight: 500;">info@merstylehub.com</a>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 20px;">
                                        <p style="margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; color: #999999; line-height: 16px;">
                                            © {{ date('Y') }} MerStyleHub. Todos los derechos reservados.
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
