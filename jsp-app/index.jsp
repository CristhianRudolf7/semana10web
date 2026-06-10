<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Contactos</title>
</head>
<body>
    <h2>Registro de Contacto</h2>
    <!-- Formulario estructurado en JSP enviando datos vía POST -->
    <form action="registro.jsp" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br><br>
        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required>
        <br><br>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
