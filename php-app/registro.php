<?php
// 1. Inicializar la sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Validación estricta del método HTTP (Código 405 si no es POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header("Content-Type: text/plain; charset=UTF-8");
    echo "Método no permitido. Esta ruta solo acepta peticiones POST.";
    exit;
}

// 3. Obtención y normalización de parámetros
$nombre_raw = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$correo_raw = isset($_POST['correo']) ? trim($_POST['correo']) : '';

// 4. Validación de datos (Campos obligatorios y formato de correo electrónico)
$correo_validado = filter_var($correo_raw, FILTER_VALIDATE_EMAIL);

if (empty($nombre_raw) || !$correo_validado) {
    http_response_code(400); // Bad Request
    header("Content-Type: text/plain; charset=UTF-8");
    echo "Datos inválidos. Por favor, complete todos los campos correctamente.";
    exit;
}

// 5. Prevención de ataques XSS mediante escape antes del almacenamiento/salida
$nombre = htmlspecialchars($nombre_raw, ENT_QUOTES, 'UTF-8');
$correo = htmlspecialchars($correo_validado, ENT_QUOTES, 'UTF-8');

// 6. Persistencia de datos en el arreglo de sesión
if (!isset($_SESSION['registros'])) {
    $_SESSION['registros'] = [];
}
$_SESSION['registros'][] = ['nombre' => $nombre, 'correo' => $correo];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado | Registro PHP</title>
    <!-- Modern Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            --panel-bg: rgba(30, 41, 59, 0.7);
            --panel-border: rgba(255, 255, 255, 0.08);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --table-border: rgba(255, 255, 255, 0.05);
            --table-row-hover: rgba(255, 255, 255, 0.02);
            --table-header-bg: rgba(99, 102, 241, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: var(--panel-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--panel-border);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 640px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header h2 {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to right, #a5b4fc, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--panel-border);
            margin-bottom: 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.95rem;
        }

        th {
            background-color: var(--table-header-bg);
            padding: 16px;
            font-weight: 600;
            color: #a5b4fc;
            border-bottom: 1px solid var(--panel-border);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--table-border);
            color: #cbd5e1;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: var(--table-row-hover);
            color: #ffffff;
        }

        .actions {
            text-align: center;
        }

        .back-link {
            display: inline-block;
            padding: 12px 24px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Registros Almacenados</h2>
            <p>Listado de contactos registrados en la sesión actual</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_SESSION['registros']) && count($_SESSION['registros']) > 0): ?>
                        <?php foreach ($_SESSION['registros'] as $registro): ?>
                            <tr>
                                <td><?php echo $registro['nombre']; ?></td>
                                <td><?php echo $registro['correo']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" style="text-align: center; color: var(--text-secondary); padding: 24px;">
                                No hay registros guardados en esta sesión.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="actions">
            <a href="index.html" class="back-link">Registrar otro contacto</a>
        </div>
    </div>
</body>
</html>
