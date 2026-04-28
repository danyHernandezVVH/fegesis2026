#!/usr/bin/env bash
set -euo pipefail

echo "📁 Creando archivos base del proyecto (Paso 0)"

# ========= Helpers =========
create_file() {
  if [[ ! -f "$1" ]]; then
    mkdir -p "$(dirname "$1")"
    echo "$2" > "$1"
    echo "✔ creado: $1"
  else
    echo "⏭ existe: $1"
  fi
}

PHP_STUB="<?php\n\n// TODO\n"
HTML_PHP_STUB="<?php\n// TODO vista\n?>\n"
SQL_STUB="-- TODO: esquema MySQL\n"

# ========= ROOT =========
create_file index.php "<?php\n// Front Controller\n"

# ========= CONFIG =========
create_file config/config.php "$PHP_STUB"
create_file config/routes.php "$PHP_STUB"
create_file config/local.php "$PHP_STUB"

# ========= CORE =========
create_file core/Router.php "$PHP_STUB"
create_file core/Controller.php "$PHP_STUB"
create_file core/Model.php "$PHP_STUB"
create_file core/Database.php "$PHP_STUB"
create_file core/Auth.php "$PHP_STUB"
create_file core/Csrf.php "$PHP_STUB"
create_file core/Validator.php "$PHP_STUB"
create_file core/Upload.php "$PHP_STUB"

# ========= CONTROLLERS =========
create_file app/controllers/SiteController.php "$PHP_STUB"
create_file app/controllers/AuthController.php "$PHP_STUB"
create_file app/controllers/AdminController.php "$PHP_STUB"
create_file app/controllers/ReservaController.php "$PHP_STUB"
create_file app/controllers/ApiController.php "$PHP_STUB"

# ========= MODELS =========
create_file app/models/Categoria.php "$PHP_STUB"
create_file app/models/Juego.php "$PHP_STUB"
create_file app/models/Usuario.php "$PHP_STUB"
create_file app/models/Reserva.php "$PHP_STUB"
create_file app/models/Publicacion.php "$PHP_STUB"

# ========= VIEWS / LAYOUTS =========
create_file app/views/layouts/header.php "$HTML_PHP_STUB"
create_file app/views/layouts/footer.php "$HTML_PHP_STUB"
create_file app/views/layouts/admin_header.php "$HTML_PHP_STUB"
create_file app/views/layouts/admin_footer.php "$HTML_PHP_STUB"

# ========= VIEWS / SITE =========
create_file app/views/site/home.php "$HTML_PHP_STUB"
create_file app/views/site/catalogo.php "$HTML_PHP_STUB"
create_file app/views/site/juego_detalle.php "$HTML_PHP_STUB"
create_file app/views/site/login.php "$HTML_PHP_STUB"
create_file app/views/site/registro.php "$HTML_PHP_STUB"
create_file app/views/site/mis_reservas.php "$HTML_PHP_STUB"

# ========= VIEWS / ADMIN =========
create_file app/views/admin/login.php "$HTML_PHP_STUB"
create_file app/views/admin/dashboard.php "$HTML_PHP_STUB"
create_file app/views/admin/categorias_list.php "$HTML_PHP_STUB"
create_file app/views/admin/categorias_form.php "$HTML_PHP_STUB"
create_file app/views/admin/juegos_list.php "$HTML_PHP_STUB"
create_file app/views/admin/juegos_form.php "$HTML_PHP_STUB"
create_file app/views/admin/reservas_list.php "$HTML_PHP_STUB"
create_file app/views/admin/publicaciones_list.php "$HTML_PHP_STUB"
create_file app/views/admin/publicaciones_form.php "$HTML_PHP_STUB"

# ========= STORAGE =========
create_file storage/schema.sql "$SQL_STUB"

echo "✅ Paso 0 completado: todos los archivos base existen."
echo "👉 Siguiente paso: Paso 1 – configuración segura de base de datos"

