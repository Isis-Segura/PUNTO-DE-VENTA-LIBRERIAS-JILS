<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>Librería JILS</title>
    {{-- Paleta JILS en línea (los correos no soportan variables CSS). Debe coincidir con pos-theme.css:
         papel #f3f0ec · crema #f5f3ef · borde #c9c1b9 · título #1d2d2c · cabecera #3b5c59 · acento #2a6d6c · apagado #5b6b6a --}}
</head>
<body style="margin:0;padding:0;background-color:#f3f0ec;font-family:'Plus Jakarta Sans','Segoe UI',Arial,sans-serif;color:#1f1f1f;-webkit-text-size-adjust:100%;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f0ec;padding:28px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="560" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:560px;background-color:#f5f3ef;border:1px solid #c9c1b9;border-radius:12px;overflow:hidden;">
                <tr>
                    <td bgcolor="#3b5c59" style="background-color:#3b5c59;padding:22px 28px 20px;border-bottom:4px solid #2a6d6c;">
                        <div style="font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#c9c1b9;">Librería JILS</div>
                        <div style="font-size:22px;line-height:1.25;font-weight:800;margin-top:6px;color:#ffffff;">{{ __('Tu cuenta ha sido creada') }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px;">
                        <p style="margin:0 0 12px;font-size:15px;line-height:1.5;color:#1d2d2c;">{{ __('Hola') }} <strong>{{ $usuario->name }}</strong>,</p>
                        <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#1f1f1f;">
                            {{ __('Un administrador te registró en el sistema de punto de venta') }}
                            <strong>{{ __('PDV JILS') }} / {{ __('Librería JILS') }}</strong>.
                        </p>

                        <p style="margin:0 0 12px;font-size:15px;line-height:1.6;color:#1f1f1f;">
                            {{ __('Estos son tus datos de acceso:') }}
                        </p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;border:1px solid #c9c1b9;border-radius:10px;margin-bottom:18px;">
                            <tr>
                                <td style="padding:14px 16px;border-bottom:1px solid #e6e0d9;">
                                    <div style="font-size:11px;font-weight:700;letter-spacing:.06em;color:#2a6d6c;text-transform:uppercase;">{{ __('Usuario / correo') }}</div>
                                    <div style="font-size:16px;font-weight:700;color:#1d2d2c;margin-top:2px;">{{ $usuario->email }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:14px 16px;border-bottom:1px solid #e6e0d9;">
                                    <div style="font-size:11px;font-weight:700;letter-spacing:.06em;color:#2a6d6c;text-transform:uppercase;">Rol</div>
                                    <div style="font-size:16px;font-weight:700;color:#1d2d2c;margin-top:2px;">{{ $rolNombre }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:14px 16px;">
                                    <div style="font-size:11px;font-weight:700;letter-spacing:.06em;color:#2a6d6c;text-transform:uppercase;">{{ __('Contraseña') }}</div>
                                    <div style="display:inline-block;margin-top:6px;padding:6px 10px;background-color:#f5f3ef;border:1px dashed #c9c1b9;border-radius:6px;font-size:16px;font-weight:700;color:#1d2d2c;font-family:Consolas,'Courier New',monospace;">{{ $passwordPlano }}</div>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 8px;font-size:14px;color:#3a4c5d;line-height:1.55;">
                            {{ __('Te recomendamos iniciar sesión y cambiar la contraseña desde tu menú de usuario') }}
                            (<em>{{ __('Cambiar contraseña') }}</em>).
                        </p>
                        <p style="margin:0;font-size:13px;color:#5b6b6a;line-height:1.5;">
                            {{ __('Si no esperabas este mensaje, ignóralo o contacta a la administración de la librería.') }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ebe7e1" style="padding:14px 28px;background-color:#ebe7e1;border-top:1px solid #c9c1b9;font-size:12px;color:#5b6b6a;text-align:center;">
                        © {{ date('Y') }} {{ __('Librería JILS') }} · {{ __('PDV JILS') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
