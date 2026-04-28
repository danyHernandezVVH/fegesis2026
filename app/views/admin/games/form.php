<?php

/** @var array $categorias */
/** @var ?array $item */
/** @var array $specValues */
/** @var string $csrf */
$bp = ($basePath ?? '');
$isEdit = !empty($item);
$categoryId = (int)($item['category_id'] ?? 0);
?>

<style>
  .card-form {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  }

  .section-title {
    font-size: 0.9rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .form-label {
    font-weight: 600;
    color: #334155;
  }

  .image-preview-wrapper {
    width: 100%;
    aspect-ratio: 16/9;
    border: 2px dashed #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    position: relative;
  }

  .image-preview-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .preview-placeholder {
    color: #94a3b8;
    text-align: center;
  }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h3 fw-bold mb-0"><?= htmlspecialchars($title ?? 'Producto') ?></h1>
  <a class="btn btn-outline-secondary btn-sm rounded-pill" href="<?= $bp ?>/admin/games">
    <i class="bi bi-arrow-left"></i> Volver
  </a>
</div>

<form method="post" action="<?= $bp ?>/admin/games/save" enctype="multipart/form-data">
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
  <input type="hidden" name="id" value="<?= (int)($item['id'] ?? 0) ?>">

  <div class="row g-4">
    <div class="col-12 col-lg-7">
      <div class="card card-form">
        <div class="card-body p-4">
          <div class="section-title"><i class="bi bi-info-circle"></i> Información General</div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Categoría *</label>
              <select class="form-select shadow-sm" name="category_id" id="category_id" required>
                <option value="">Seleccionar...</option>
                <?php foreach ($categorias as $c): ?>
                  <option value="<?= (int)$c['id'] ?>" <?= ((int)$c['id'] === $categoryId) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Precio base *</label>
              <div class="input-group shadow-sm">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control" name="precio_base" required min="1" value="<?= (int)($item['precio_base'] ?? 0) ?>">
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">Nombre del Juego *</label>
              <input class="form-control shadow-sm" name="nombre" required maxlength="150" value="<?= htmlspecialchars($item['nombre'] ?? '') ?>">
            </div>
            <div class="col-12">
              <label class="form-label">Slug (URL amigable)</label>
              <input class="form-control shadow-sm bg-light" name="slug" required maxlength="160" placeholder="ej: tobogan-nemo" value="<?= htmlspecialchars($item['slug'] ?? '') ?>">
            </div>
          </div>

          <div class="mt-4 section-title"><i class="bi bi-card-text"></i> Contenido</div>
          <div class="mb-3">
            <label class="form-label">Descripción Detallada</label>
            <textarea class="form-control shadow-sm" name="descripcion" rows="4"><?= htmlspecialchars($item['descripcion'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Requisitos / Incluye</label>
            <textarea class="form-control shadow-sm" name="requisitos" rows="3"><?= htmlspecialchars($item['requisitos'] ?? '') ?></textarea>
          </div>

          <div class="mt-4 p-3 bg-light rounded-4">
            <div class="fw-bold mb-3 small text-uppercase text-muted">Medidas Técnicas (cm)</div>
            <div class="row g-2">
              <div class="col-4">
                <label class="form-label small">Largo</label>
                <input type="number" class="form-control shadow-sm" name="largo_cm" value="<?= htmlspecialchars((string)($item['largo_cm'] ?? '')) ?>">
              </div>
              <div class="col-4">
                <label class="form-label small">Ancho</label>
                <input type="number" class="form-control shadow-sm" name="ancho_cm" value="<?= htmlspecialchars((string)($item['ancho_cm'] ?? '')) ?>">
              </div>
              <div class="col-4">
                <label class="form-label small">Alto</label>
                <input type="number" class="form-control shadow-sm" name="alto_cm" value="<?= htmlspecialchars((string)($item['alto_cm'] ?? '')) ?>">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

   <div class="col-12 col-lg-5">
      <div class="card card-form h-100">
        <div class="card-body p-4">
          <div class="section-title"><i class="bi bi-image"></i> Imagen Principal</div>

          <div class="image-preview-wrapper mb-3" id="preview_container">
            <?php if (!empty($item['cover_image'])): ?>
              <img src="<?= htmlspecialchars($bp . '/' . ltrim((string)$item['cover_image'], '/')) ?>" id="main_preview">
            <?php else: ?>
              <div class="preview-placeholder" id="placeholder_text">
                <i class="bi bi-cloud-upload fs-1"></i>
                <p class="mb-0 small">Sin imagen seleccionada</p>
              </div>
              <img src="" id="main_preview" style="display:none;">
            <?php endif; ?>
          </div>

          <div class="mb-4">
            <label class="form-label small">Cambiar fotografía</label>
            <input type="file" class="form-control shadow-sm" name="cover_image" id="cover_image_input" accept="image/*">
            <div class="form-text">Formatos sugeridos: JPG, PNG o WEBP. Máx 4MB.</div>
          </div>

          <hr class="my-4">

          <div class="section-title"><i class="bi bi-eye"></i> Visibilidad</div>
          <div class="p-3 bg-light rounded-4 mb-4">
             <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_published" id="is_published" 
                    <?= (!empty($item['is_published'])) ? 'checked' : '' ?>>
                <label class="form-check-label fw-bold" for="is_published">Publicar en la web</label>
             </div>
             <div class="text-muted small mt-1">Si está apagado, el juego se guardará como borrador oculto.</div>
          </div>

          <div class="section-title"><i class="bi bi-list-stars"></i> Atributos Dinámicos</div>
          <div id="spec_container" class="p-3 bg-light rounded-4">
            <div class="text-muted small">Cargando atributos de categoría...</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex gap-2 mt-4">
    <button class="btn btn-primary btn-lg px-5 fw-bold rounded-pill shadow">Guardar Cambios</button>
    <a class="btn btn-light btn-lg px-4 rounded-pill border" href="<?= $bp ?>/admin/games">Cancelar</a>
  </div>
  <div class="card mb-4">
</div>

</form>

<script>
  (function() {
    const basePath = <?= json_encode($bp) ?>;
    const existing = <?= json_encode($specValues ?? []) ?>;

    const $cat = document.getElementById('category_id');
    const $spec = document.getElementById('spec_container');
    const $imgInput = document.getElementById('cover_image_input');
    const $mainPreview = document.getElementById('main_preview');
    const $placeholder = document.getElementById('placeholder_text');

    // FUNCIONALIDAD: Previsualización de Imagen
    $imgInput.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          $mainPreview.src = e.target.result;
          $mainPreview.style.display = 'block';
          if ($placeholder) $placeholder.style.display = 'none';
        }
        reader.readAsDataURL(file);
      }
    });

    // FUNCIONALIDAD: Atributos Dinámicos (Tu lógica existente mejorada)
    async function loadSpecs(categoryId) {
      if (!categoryId) {
        $spec.innerHTML = `<div class="text-muted small">Selecciona una categoría para ver atributos adicionales.</div>`;
        return;
      }
      const url = `${basePath}/admin/api/category-specs/${categoryId}`;
      try {
        const res = await fetch(url);
        const data = await res.json();
        const fields = (data && data.fields) ? data.fields : [];
        if (!fields.length) {
          $spec.innerHTML = `<div class="text-muted small">Sin atributos específicos.</div>`;
          return;
        }
        $spec.innerHTML = fields.map(f => {
          const saved = (existing && existing[f.id] !== undefined) ? existing[f.id] : '';
          return `<div class="mb-2">
                        <label class="form-label small mb-1">${f.label || f.field_key}</label>
                        <input class="form-control form-control-sm" name="spec[${f.id}]" value="${saved}">
                    </div>`;
        }).join('');
      } catch (e) {
        $spec.innerHTML = "Error al cargar.";
      }
    }

    $cat.addEventListener('change', function() {
      loadSpecs(this.value);
    });
    loadSpecs(<?= (int)$categoryId ?>);
  })();
</script>