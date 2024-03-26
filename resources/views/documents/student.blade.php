<!-- resources/views/student.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Información del Estudiante</title>
    <style>
        h1 {
            color: #333;
            font-family: Arial, sans-serif;
        }
        p {
            color: #666;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <h1>Información del Estudiante</h1>

    <p><strong>Nombre:</strong> {{ $student->name }}</p>
    <p><strong>Apellido:</strong> {{ $student->surname }}</p>
    <p><strong>Email:</strong> {{ $student->email }}</p>
    

</body>
</html>