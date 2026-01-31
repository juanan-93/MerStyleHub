<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Confirmación de Cita - MerStyleHub</title>
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
        /* Reset styles */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }
        
        /* Responsive styles */
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .fluid { max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important; }
            .stack-column, .stack-column-center { display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important; }
            .stack-column-center { text-align: center !important; }
            .mobile-padding { padding-left: 20px !important; padding-right: 20px !important; }
            .mobile-padding-top { padding-top: 30px !important; }
            .mobile-padding-bottom { padding-bottom: 30px !important; }
            .mobile-center { text-align: center !important; }
            .mobile-font-large { font-size: 22px !important; line-height: 28px !important; }
            .mobile-font-medium { font-size: 16px !important; line-height: 24px !important; }
            .mobile-button { width: 100% !important; max-width: 300px !important; }
            .info-box { margin: 0 15px !important; }
            .icon-cell { width: 50px !important; }
            .icon-img { width: 24px !important; height: 24px !important; }
        }
        
        @media only screen and (max-width: 480px) {
            .mobile-padding { padding-left: 15px !important; padding-right: 15px !important; }
            .mobile-font-large { font-size: 20px !important; line-height: 26px !important; }
            .mobile-font-small { font-size: 13px !important; line-height: 20px !important; }
            .header-logo { width: 120px !important; height: auto !important; }
            .appointment-card { margin: 0 10px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #ECE9E2; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <!-- Preheader Text -->
    <div style="display: none; font-size: 1px; color: #ECE9E2; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
        ¡Tu cita ha sido confirmada! Te esperamos el {{ $formattedDate }} a las {{ Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}h
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
                                
                                <!-- Success Icon & Title -->
                                <tr>
                                    <td align="center" class="mobile-padding-top" style="padding: 40px 30px 20px 30px;">
                                        <!-- Checkmark Circle -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td align="center" style="width: 70px; height: 70px; background-color: #D4EDDA; border-radius: 50%;">
                                                    <span style="font-size: 32px; line-height: 70px;">✓</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td align="center" style="padding: 10px 30px 5px 30px;">
                                        <h1 class="mobile-font-large" style="margin: 0; font-family: 'Georgia', 'Times New Roman', serif; font-size: 26px; font-weight: 600; color: #343434; line-height: 32px;">
                                            ¡Cita Confirmada!
                                        </h1>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td align="center" style="padding: 0 30px 25px 30px;">
                                        <p class="mobile-font-medium" style="margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; color: #6C757D; line-height: 22px;">
                                            Hola <strong style="color: #343434;">{{ $appointment->client_name }}</strong>, tu reserva se ha realizado correctamente
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
                                
                                <!-- Appointment Details Card -->
                                <tr>
                                    <td class="mobile-padding" style="padding: 25px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" class="appointment-card" style="background-color: #F8F6F3; border-radius: 12px; border-left: 4px solid #A08A7A;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    
                                                    <!-- Section Title -->
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="padding-bottom: 15px;">
                                                                <span style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; font-weight: 600; color: #A08A7A; text-transform: uppercase; letter-spacing: 1px;">
                                                                    Detalles de tu cita
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    
                                                    <!-- Date -->
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td class="icon-cell" valign="top" style="width: 40px; padding-right: 12px;">
                                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                                    <tr>
                                                                        <td style="width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 8px; text-align: center; vertical-align: middle;">
                                                                            <span style="font-size: 18px; line-height: 36px;">📅</span>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td valign="middle" style="padding-bottom: 12px;">
                                                                <span style="display: block; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; color: #6C757D; margin-bottom: 2px;">Fecha</span>
                                                                <span class="mobile-font-small" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; font-weight: 600; color: #343434; text-transform: capitalize;">{{ $formattedDate }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    
                                                    <!-- Time -->
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td class="icon-cell" valign="top" style="width: 40px; padding-right: 12px;">
                                                                <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                                    <tr>
                                                                        <td style="width: 36px; height: 36px; background-color: #FFFFFF; border-radius: 8px; text-align: center; vertical-align: middle;">
                                                                            <span style="font-size: 18px; line-height: 36px;">🕐</span>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td valign="middle">
                                                                <span style="display: block; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; color: #6C757D; margin-bottom: 2px;">Horario</span>
                                                                <span class="mobile-font-small" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; font-weight: 600; color: #343434;">{{ $formattedTime }}h</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                                <!-- Client Info Section -->
                                <tr>
                                    <td class="mobile-padding" style="padding: 0 30px 25px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <span style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 11px; font-weight: 600; color: #A08A7A; text-transform: uppercase; letter-spacing: 1px;">
                                                        Tus datos de contacto
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <!-- Email -->
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="width: 24px; vertical-align: middle;">
                                                                <span style="font-size: 14px;">✉️</span>
                                                            </td>
                                                            <td class="mobile-font-small" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #343434; padding-left: 10px;">
                                                                {{ $appointment->client_email }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <!-- Phone -->
                                            <tr>
                                                <td style="padding: 8px 0;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="width: 24px; vertical-align: middle;">
                                                                <span style="font-size: 14px;">📱</span>
                                                            </td>
                                                            <td class="mobile-font-small" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; color: #343434; padding-left: 10px;">
                                                                {{ $appointment->client_phone }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
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
                                
                                <!-- Important Notice -->
                                <tr>
                                    <td class="mobile-padding" style="padding: 25px 30px 20px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #FFF9E6; border-radius: 10px; border: 1px solid #FFE69C;">
                                            <tr>
                                                <td style="padding: 16px 18px;">
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                                        <tr>
                                                            <td style="width: 28px; vertical-align: top; padding-top: 2px;">
                                                                <span style="font-size: 18px;">💡</span>
                                                            </td>
                                                            <td style="padding-left: 10px;">
                                                                <span style="display: block; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; font-weight: 600; color: #856404; margin-bottom: 4px;">Recordatorio importante</span>
                                                                <span class="mobile-font-small" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #856404; line-height: 19px;">
                                                                    Si necesitas cancelar o modificar tu cita, por favor hazlo con al menos 24 horas de anticipación.
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Cancel Button -->
                                <tr>
                                    <td align="center" class="mobile-padding mobile-padding-bottom" style="padding: 10px 30px 35px 30px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <span style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #6C757D;">
                                                        ¿Necesitas cancelar tu cita?
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td class="mobile-button" style="border-radius: 8px; background-color: #FFFFFF; border: 2px solid #D9D4CE;">
                                                    <a href="{{ $appointment->cancellation_url }}" target="_blank" style="display: inline-block; padding: 12px 28px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 14px; font-weight: 600; color: #6C757D; text-decoration: none;">
                                                        Cancelar cita
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
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
