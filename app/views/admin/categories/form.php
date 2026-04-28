<?php

/**
 * Formulario de Categoría - Fegesis Manager
 * * @var ?array $categoria Datos de la categoría si es edición
 * @var string $title     Título de la página
 * @var string $csrf      Token de seguridad CSRF
 */
$bp = ($basePath ?? '');
$isEdit = !empty($categoria);
?>

<style>
  :root {
    --fegesis-primary: #0d6efd;
    --fegesis-bg: #f8fafc;
  }

  .card-form {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  }

  /* Títulos de sección */
  .section-header {
    font-size: 0.85rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #64748b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .section-header i {
    color: var(--fegesis-primary);
  }

  /* Inputs y Etiquetas */
  .form-label {
    font-weight: 700;
    font-size: 0.85rem;
    color: #334155;
    margin-bottom: 0.5rem;
  }

  .form-control {
    border-radius: 12px;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
  }

  .form-control:focus {
    border-color: var(--fegesis-primary);
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
  }

  /* Slug Dinámico */
  .slug-input-group .input-group-text {
    border-radius: 12px 0 0 12px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    color: #94a3b8;
  }

  .slug-field {
    font-family: 'Monaco', 'Consolas', monospace;
    font-size: 0.9rem;
    background-color: #f8fafc !important;
  }

  .slug-field:not([readonly]) {
    background-color: #fff !important;
    color: var(--fegesis-primary);
    border-color: var(--fegesis-primary);
  }

  .btn-unlock {
    border-radius: 0 12px 12px 0;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    transition: all 0.2s;
  }

  /* Botonera */
  .btn-save {
    border-radius: 12px;
    padding: 0.8rem 2.5rem;
    font-weight: 700;
    transition: all 0.2s;
  }

  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
  }
</style>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-7">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-1 fw-bold text-dark"><?= htmlspecialchars($title) ?></h1>
          <p class="text-muted small mb-0">Configuración técnica y visual de la categoría.</p>
        </div>
        <a class="btn btn-link text-decoration-none fw-bold text-muted" href="<?= $bp ?>/admin/categories">
          <i class="bi bi-x-lg me-1"></i> Cancelar
        </a>
      </div>

      <div class="card card-form">
        <div class="card-body p-4 p-md-5">
          <form method="post" action="<?= $bp ?>/admin/categories/save" id="categoryForm">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
            <input type="hidden" name="id" value="<?= (int)($categoria['id'] ?? 0) ?>">

            <div class="section-header"><i class="bi bi-info-square-fill"></i> Identidad de la Categoría</div>

            <div class="mb-4">
              <label class="form-label">Nombre Público *</label>
              <input class="form-control shadow-sm" name="nombre" id="nombreInput" required
                placeholder="Ej: Camas Elásticas" autocomplete="off"
                value="<?= htmlspecialchars($categoria['nombre'] ?? '') ?>">
              <div class="form-text mt-2 small">Este nombre será el que vean los clientes en el menú de navegación.</div>
            </div>

            <div class="mb-5">
              <label class="form-label">Enlace Permanente (Slug)</label>
              <div class="input-group slug-input-group">
                <span class="input-group-text border-end-0">/</span>
                <input class="form-control shadow-sm border-start-0 slug-field"
                  name="slug" id="slugInput" required readonly
                  placeholder="generando-enlace..."
                  value="<?= htmlspecialchars($categoria['slug'] ?? '') ?>">
                <button class="btn btn-unlock" type="button" id="toggleSlug" title="Editar enlace manualmente">
                  <i class="bi bi-lock-fill" id="lockIcon"></i>
                </button>
              </div>
              <div class="form-text mt-2 lh-sm small">
                <i class="bi bi-info-circle me-1"></i>
                <b>Generación automática:</b> Se basa en el nombre para optimizar buscadores (SEO).
                No requiere edición manual salvo casos especiales.
              </div>
            </div>

            <div class="section-header"><i class="bi bi-sliders"></i> Organización y Estado</div>

            <div class="row g-4 mb-5">
              <div class="col-md-6">
                <label class="form-label">Prioridad de Lista (Orden)</label>
                <input type="number" class="form-control shadow-sm" name="orden" min="0"
                  value="<?= (int)($categoria['orden'] ?? 0) ?>">
                <div class="form-text mt-2 lh-sm small">
                  Define la posición en el sitio. <b>Números bajos aparecen primero</b> (0 es la máxima prioridad).
                </div>
              </div>
              <div class="col-md-6 d-flex align-items-end">
                <div class="bg-light p-3 rounded-4 border w-100">
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                      style="cursor: pointer; width: 2.5em; height: 1.25em;"
                      <?= (!isset($categoria) || ($categoria['is_active'] ?? 1)) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold ms-2 text-dark" for="is_active" style="cursor: pointer;">
                      Categoría Activa
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex flex-column flex-md-row gap-3 pt-3 border-top">
              <button type="submit" class="btn btn-primary btn-save flex-grow-1 shadow-sm rounded-pill">
                <i class="bi bi-check-circle-fill me-2"></i> Guardar Configuración
              </button>
              <a class="btn btn-light border px-4 rounded-pill fw-bold text-muted" href="<?= $bp ?>/admin/categories">
                Salir sin guardar
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function() {
    const nombreInp = document.getElementById('nombreInput');
    const slugInp = document.getElementById('slugInput');
    const toggleBtn = document.getElementById('toggleSlug');
    const lockIcon = document.getElementById('lockIcon');

    // Detectamos si es edición para no sobreescribir slugs existentes por error
    const isEditMode = <?= json_encode($isEdit) ?>;
    let manualEdit = false;

    /**
     * Convierte texto plano en un Slug válido para URLs
     */
    function generateSlug(text) {
      return text.toString().toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Elimina acentos
        .replace(/\s+/g, '-') // Espacios por guiones
        .replace(/[^\w\-]+/g, '') // Elimina caracteres no permitidos
        .replace(/\-\-+/g, '-') // Elimina guiones dobles
        .replace(/^-+/, '') // Limpia guiones al inicio
        .replace(/-+$/, ''); // Limpia guiones al final
    }

    // Generación en tiempo real si no es edición o si no se ha activado modo manual
    nombreInp.addEventListener('input', function() {
      if (!manualEdit && !isEditMode) {
        slugInp.value = generateSlug(this.value);
      }
    });

    // Lógica del candado para edición manual del slug
    toggleBtn.addEventListener('click', function() {
      manualEdit = !manualEdit;
      if (manualEdit) {
        slugInp.removeAttribute('readonly');
        slugInp.focus();
        lockIcon.className = 'bi bi-unlock-fill text-primary';
        this.classList.add('border-primary');
      } else {
        slugInp.setAttribute('readonly', true);
        lockIcon.className = 'bi bi-lock-fill';
        this.classList.remove('border-primary');
        // Si el usuario vuelve a bloquear sin haber editado, refrescamos con el nombre
        if (!isEditMode && !slugInp.value) slugInp.value = generateSlug(nombreInp.value);
      }
    });
  })();
</script>