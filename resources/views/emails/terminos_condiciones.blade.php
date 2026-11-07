<!DOCTYPE html>
<html lang="es">
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
                        <div style="font-size:22px;line-height:1.25;font-weight:800;margin-top:6px;color:#ffffff;">¡Bienvenido!</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px;">
                        <p style="margin:0 0 12px;font-size:15px;color:#1d2d2c;">Hola <strong>{{ $usuario->name }}</strong>,</p>
                        <p style="margin:0 0 18px;font-size:15px;line-height:1.6;color:#1f1f1f;">
                            Un administrador te registró en el sistema de punto de venta
                            <strong>PDV JILS / Librería JILS</strong>.
                        </p>
                        <p style="margin:0 0 22px;font-size:15px;line-height:1.6;color:#1f1f1f;">
                            Para ver tus datos de acceso (usuario, contraseña y rol) primero debes leer y
                            aceptar los términos y condiciones. Presiona el botón para continuar:
                        </p>

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto 22px;">
                            <tr>
                                <td align="center" bgcolor="#2a6d6c" style="background-color:#2a6d6c;border-radius:10px;">
                                    <a href="{{ $urlAceptar }}" style="display:inline-block;padding:14px 28px;font-size:16px;font-weight:800;color:#ffffff;text-decoration:none;">
                                        Ver términos y condiciones
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 12px;font-size:13px;color:#5b6b6a;line-height:1.5;">
                            Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                            <a href="{{ $urlAceptar }}" style="color:#2a6d6c;word-break:break-all;">{{ $urlAceptar }}</a>
                        </p>
                        <p style="margin:0 0 8px;font-size:13px;color:#5b6b6a;line-height:1.5;">
                            Este enlace es personal: no lo compartas, porque desde ahí se muestran tus datos de acceso
                            una vez que aceptas. Vence en {{ \App\Models\User::DIAS_VIGENCIA_TERMINOS }} días.
                        </p>
                        <p style="margin:0;font-size:13px;color:#5b6b6a;line-height:1.5;">
                            Si no esperabas este mensaje, ignóralo o contacta a la administración de la librería.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ebe7e1" style="padding:14px 28px;background-color:#ebe7e1;border-top:1px solid #c9c1b9;font-size:12px;color:#5b6b6a;text-align:center;">
                        © {{ date('Y') }} Librería JILS · PDV JILS
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
