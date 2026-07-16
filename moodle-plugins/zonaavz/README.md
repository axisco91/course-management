# Puente `local_zonaavz`

Compatible con `local_mail` 1.9.10 y Moodle 3.0–3.10.

## Instalación

1. Copiar esta carpeta como `<moodle>/local/zonaavz`.
2. Ejecutar la actualización de Moodle o entrar en Administración del sitio > Notificaciones.
3. Habilitar el protocolo REST.
4. Añadir `local_zonaavz_send_mail` al servicio externo que utiliza el token guardado en ZonaAvz.
5. Conceder `local/zonaavz:sendmail` al usuario propietario del token en el contexto del sistema.
6. Matricular ese usuario técnico en los cursos y concederle `local/mail:usemail`.
7. Habilitar la salida por correo para las notificaciones `local_mail` en Moodle.

El mismo servicio externo debe conservar las funciones Moodle que ZonaAvz ya utiliza para consultar cursos, usuarios, matrículas, finalización y dedicación.
