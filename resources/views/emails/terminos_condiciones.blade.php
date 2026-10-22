<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Librería JILS</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Segoe UI,Arial,sans-serif;color:#0f172a;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 12px;">
    <tr>
        <td align="center">
            <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(15,23,42,.08);">
                <tr>
                    <td style="background:linear-gradient(135deg,#1e3a8a,#3b82f6);padding:20px 28px;color:#fff;">
                        <div style="font-size:13px;opacity:.9;letter-spacing:.06em;text-transform:uppercase;">Librería JILS</div>
                        <div style="font-size:22px;font-weight:700;margin-top:4px;">¡Bienvenido!</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px;">
                        <p style="margin:0 0 12px;font-size:15px;">Hola <strong>{{ $usuario->name }}</strong>,</p>
                        <p style="margin:0 0 18px;font-size:15px;line-height:1.5;color:#334155;">
                            Un administrador te registró en el sistema de punto de venta
                            <strong>PDV JILS / Librería JILS</strong>.
                        </p>
                        <p style="margin:0 0 22px;font-size:15px;line-height:1.5;color:#334155;">
                            Para ver tus datos de acceso (usuario, contraseña y rol) primero debes leer y
                            aceptar los términos y condiciones. Presiona el botón para continuar:
                        </p>

                        <table cellpadding="0" cellspacing="0" style="margin:0 auto 22px;">
                            <tr>
                                <td align="center" style="background:#2563eb;border-radius:8px;">
                                    <a href="{{ $urlAceptar }}" style="display:inline-block;padding:14px 28px;font-size:16px;font-weight:700;color:#ffffff;text-decoration:none;">
                                        Ver términos y condiciones
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 12px;font-size:13px;color:#64748b;line-height:1.5;">
                            Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
                            <a href="{{ $urlAceptar }}" style="color:#2563eb;word-break:break-all;">{{ $urlAceptar }}</a>
                        </p>
                        <p style="margin:0 0 8px;font-size:13px;color:#64748b;line-height:1.5;">
                            Este enlace es personal: no lo compartas, porque desde ahí se muestran tus datos de acceso
                            una vez que aceptas. Vence en {{ \App\Models\User::DIAS_VIGENCIA_TERMINOS }} días.
                        </p>
                        <p style="margin:0;font-size:13px;color:#94a3b8;">
                            Si no esperabas este mensaje, ignóralo o contacta a la administración de la librería.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:14px 28px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:12px;color:#94a3b8;text-align:center;">
                        © {{ date('Y') }} Librería JILS · PDV JILS
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
