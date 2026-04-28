#!/usr/bin/env bash
set -euo pipefail

# ====== Config ======
PROJECT_ROOT="$(pwd)"

echo "Creando estructura en: $PROJECT_ROOT"

# ====== Carpetas base ======
mkdir -p app/controllers app/models app/views/{layouts,site,admin}
mkdir -p config core
mkdir -p public/assets/{css,js,img,vendor}
mkdir -p public/uploads/{games,posts}
mkdir -p storage/{logs}

# ====== Archivos placeholder (para Git y orden) ======
touch app/controllers/.gitkeep app/models/.gitkeep
touch app/views/layouts/.gitkeep app/views/site/.gitkeep app/views/admin/.gitkeep
touch public/assets/css/.gitkeep public/assets/js/.gitkeep public/assets/img/.gitkeep public/assets/vendor/.gitkeep
touch public/uploads/games/.gitkeep public/uploads/posts/.gitkeep
touch storage/logs/.gitkeep

# ====== Archivos “stub” opcionales (si no existen) ======
# Controllers
for f in AdminController.php AuthController.php SiteController.php ReservaController.php ApiController.php; do
  [[ -f "app/controllers/$f" ]] || cat > "app/controllers/$f" <<'PHP'
<?php
// TODO: Implementar controlador
PHP
done

# Models
for f in Categoria.php Juego.php Usuario.php Reserva.php Publicacion.php; do
  [[ -f "app/models/$f" ]] || cat > "app/models/$f" <<'PHP'
<?php
// TODO: Implementar modelo
PHP
done

# Views - Layouts
for f in header.php footer.php admin_header.php admin_footer.php; do
  [[ -f "app/views/layouts/$f" ]] || cat > "app/views/layouts/$f" <<'PHP'
<?php
// TODO: Implementar layout
PHP
done

# Views - Site
for f in home.php catalogo.php juego_detalle.php login.php registro.php mis_reservas.php; do
  [[ -f "app/views/site/$f" ]] || cat > "app/views/site/$f" <<'PHP'
<?php
// TODO: Implementar vista
PHP
done

# Views - Admin
for f in dashboard.php categorias_list.php categorias_form.php juegos_list.php juegos_form.php reservas_list.php publicaciones_list.php publicaciones_form.php; do
  [[ -f "app/views/admin/$f" ]] || cat > "app/views/admin/$f" <<'PHP'
<?php
// TODO: Implementar vista admin
PHP
done

# ====== SQL base ======
if [[ ! -f storage/schema.sql ]]; then
  cat > storage/schema.sql <<'SQL'
-- TODO: Pegar aquí el esquema MySQL (tables: admins, users, categories, games, game_images, reservations, reservation_blocks, posts, etc.)
SQL
fi

# ====== .htaccess (Apache) ======
# 1) Bloquear acceso a carpetas sensibles
cat > app/.htaccess <<'HT'
Deny from all
HT

cat > core/.htaccess <<'HT'
Deny from all
HT

cat > config/.htaccess <<'HT'
Deny from all
HT

cat > storage/.htaccess <<'HT'
Deny from all
HT

# 2) En uploads: evitar listado de directorios
cat > public/uploads/.htaccess <<'HT'
Options -Indexes
HT

# ====== Mensaje final ======
echo "✅ Estructura creada."
echo "Revisa que tu proyecto use public/ como DocumentRoot en producción (recomendado)."
