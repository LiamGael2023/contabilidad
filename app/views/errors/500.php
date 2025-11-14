<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error del servidor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .error-content {
            text-align: center;
        }
        .error-icon {
            font-size: 120px;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="error-content">
        <div class="error-icon">
            <i class="bi bi-x-circle"></i>
        </div>
        <h1 class="display-1">500</h1>
        <h2>Error del servidor</h2>
        <p class="lead">Ha ocurrido un error interno. Por favor, intenta nuevamente.</p>
        <a href="/dashboard" class="btn btn-primary">
            <i class="bi bi-house"></i> Volver al inicio
        </a>
    </div>
</body>
</html>
