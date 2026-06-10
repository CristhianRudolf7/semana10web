<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="java.util.*" %>
<%@ taglib uri="http://java.sun.com/jsp/pub/jstl/core" prefix="c" %>
<%
    // 2. Validación del Método HTTP
    if (!"POST".equalsIgnoreCase(request.getMethod())) {
        response.setStatus(405); // Method Not Allowed
        out.println("Método no permitido");
        return;
    }

    // 3. Validación de Datos
    String nombre = request.getParameter("nombre");
    String correo = request.getParameter("correo");

    if (nombre == null || nombre.trim().isEmpty() || correo == null || correo.trim().isEmpty()) {
        response.setStatus(400); // Bad Request
        out.println("Datos inválidos");
        return;
    }

    // 4. Persistencia en Sesión
    // Recuperar la lista de registros de la sesión
    List<Map<String, String>> registros = (List<Map<String, String>>) session.getAttribute("registros");
    if (registros == null) {
        registros = new ArrayList<>();
    }

    // Insertar un nuevo mapa en la lista
    Map<String, String> nuevoRegistro = new HashMap<>();
    nuevoRegistro.put("nombre", nombre);
    nuevoRegistro.put("correo", correo);
    registros.add(nuevoRegistro);

    // Guardar la lista actualizada en la sesión
    session.setAttribute("registros", registros);
%>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registros Guardados</title>
</head>
<body>
    <h2>Contactos Registrados</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
            </tr>
        </thead>
        <tbody>
            <!-- 5. Renderizado Seguro de la Tabla usando JSTL -->
            <c:forEach var="registro" items="${sessionScope.registros}">
                <tr>
                    <td><c:out value="${registro.nombre}"/></td>
                    <td><c:out value="${registro.correo}"/></td>
                </tr>
            </c:forEach>
        </tbody>
    </table>
    <br>
    <a href="index.jsp">Registrar otro contacto</a>
</body>
</html>
