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
                        <div style="font-size:22px;font-weight:700;margin-top:4px;">Tu cuenta ha sido creada</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px;">
                        <p style="margin:0 0 12px;font-size:15px;">Hola <strong>{{ $usuario->name }}</strong>,</p>
                        <p style="margin:0 0 18px;font-size:15px;line-height:1.5;color:#334155;">
                            Un administrador te registró en el sistema de punto de venta
                            <strong>PDV JILS / Librería JILS</strong>. Estos son tus datos de acceso:
                        </p>

                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:18px;">
                            <tr>
                                <td style="padding:14px 16px;border-bottom:1px solid #e2e8f0;">
                                    <div style="font-size:12px;color:#64748b;text-transform:uppercase;">Usuario / correo</div>
                                    <div style="font-size:16px;font-weight:600;">{{ $usuario->email }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:14px 16px;border-bottom:1px solid #e2e8f0;">
                                    <div style="font-size:12px;color:#64748b;text-transform:uppercase;">Rol</div>
                                    <div style="font-size:16px;font-weight:600;">{{ $rolNombre }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:14px 16px;">
                                    <div style="font-size:12px;color:#64748b;text-transform:uppercase;">Contraseña temporal</div>
                                    <div style="font-size:16px;font-weight:600;font-family:Consolas,monospace;">{{ $passwordPlano }}</div>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 8px;font-size:14px;color:#475569;line-height:1.5;">
                            Te recomendamos iniciar sesión y cambiar la contraseña desde tu menú de usuario
                            (<em>Cambiar contraseña</em>).
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
