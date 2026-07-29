# Puente `local_zonaavz`

Compatible con Moodle 3.9 y 4.1. La versión de `local_mail` debe ser compatible con la versión de Moodle instalada.

## Instalación

1. Copiar esta carpeta como `<moodle>/local/zonaavz`.
2. Ejecutar la actualización de Moodle o entrar en Administración del sitio > Notificaciones.
3. Habilitar el protocolo REST.
4. Añadir al servicio externo existente las funciones `local_zonaavz_send_mail`, `local_zonaavz_site_info`, `local_zonaavz_list_courses`, `local_zonaavz_list_categories`, `local_zonaavz_provision_course` y `core_user_get_users`.
5. Conceder `local/zonaavz:sendmail` al usuario propietario del token en el contexto del sistema.
6. Conceder al usuario técnico los permisos necesarios para duplicar cursos, administrar usuarios y matrículas y utilizar `local_mail`.
7. Habilitar la salida por correo para las notificaciones `local_mail` en Moodle.

El servicio debe conservar también las funciones Moodle utilizadas por ZonaAvz para consultar cursos, usuarios, matrículas y finalización. `local_dedication_get_dedication` se añadirá únicamente cuando el plugin `local_dedication` esté instalado.
