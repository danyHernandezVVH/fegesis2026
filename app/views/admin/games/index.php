<?php

/** @var array $items */
/** @var string $csrf */
$bp = ($basePath ?? '');

// Funciones de ayuda
function money(int $v): string
{
  return '$' . number_format($v, 0, ',', '.');
}

// Conteo para KPIs
$totalItems = count($items);
$publishedCount = 0;
$categories = [];
foreach ($items as $it) {
  if ((int)$it['is_published'] === 1) $publishedCount++;
  if (!empty($it['categoria'])) $categories[$it['categoria']] = true;
}
?>

<style>
  :root {
    --fegesis-primary: #0d6efd;
    --fegesis-gray: #64748b;
  }

  .kpi-card-game {
    border: none;
    border-radius: 16px;
    background: #fff;
    transition: transform 0.2s;
  }

  .icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Tabla Estilo Catálogo */
  .table-games thead th {
    background: #f8fafc;
    padding: 12px 15px;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--fegesis-gray);
    border-bottom: 2px solid #edf2f7;
  }

  .table-games tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
  }

  .table-games tbody tr:hover {
    background-color: #f8fbff;
  }

  .game-thumb {
    width: 80px;
    height: 55px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .game-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0;
  }

  .game-slug {
    font-size: 0.75rem;
    color: #94a3b8;
    font-family: monospace;
  }

  .badge-category {
    background: #f1f5f9;
    color: #475569;
    font-weight: 700;
    font-size: 0.7rem;
    padding: 4px 10px;
    border-radius: 6px;
  }

  .price-tag {
    font-weight: 800;
    color: #0f172a;
    font-size: 0.95rem;
  }

  .dim-tag {
    font-size: 0.75rem;
    color: #64748b;
    display: flex;
    gap: 4px;
  }

  .dim-val {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 1px 5px;
    border-radius: 4px;
    font-weight: 600;
  }

  @media (max-width: 768px) {
    .hide-mobile {
      display: none;
    }
  }
</style>

<div class="row align-items-center mb-4">
  <div class="col">
    <h1 class="h3 mb-1 fw-bold text-dark">Inventario de Juegos</h1>
    <p class="text-muted small mb-0"><i class="bi bi-box-seam me-1"></i> Gestiona el catálogo de productos y su disponibilidad pública.</p>
  </div>
  <div class="col-auto">
    <a class="btn btn-primary fw-bold shadow-sm px-4 rounded-3" href="<?= $bp ?>/admin/games/create">
      <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
    </a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card kpi-card-game shadow-sm">
      <div class="card-body d-flex align-items-center py-3">
        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3"><i class="bi bi-controller fs-4"></i></div>
        <div>
          <div class="text-muted small fw-bold" style="font-size: 0.6rem;">TOTAL JUEGOS</div>
          <div class="h4 mb-0 fw-bold"><?= $totalItems ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card kpi-card-game shadow-sm">
      <div class="card-body d-flex align-items-center py-3">
        <div class="icon-circle bg-success bg-opacity-10 text-success me-3"><i class="bi bi-eye fs-4"></i></div>
        <div>
          <div class="text-muted small fw-bold" style="font-size: 0.6rem;">PUBLICADOS</div>
          <div class="h4 mb-0 fw-bold"><?= $publishedCount ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card kpi-card-game shadow-sm">
      <div class="card-body d-flex align-items-center py-3">
        <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3"><i class="bi bi-pencil-square fs-4"></i></div>
        <div>
          <div class="text-muted small fw-bold" style="font-size: 0.6rem;">BORRADORES</div>
          <div class="h4 mb-0 fw-bold"><?= $totalItems - $publishedCount ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card kpi-card-game shadow-sm">
      <div class="card-body d-flex align-items-center py-3">
        <div class="icon-circle bg-info bg-opacity-10 text-info me-3"><i class="bi bi-tags fs-4"></i></div>
        <div>
          <div class="text-muted small fw-bold" style="font-size: 0.6rem;">CATEGORÍAS</div>
          <div class="h4 mb-0 fw-bold"><?= count($categories) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
  <div class="card-body p-3">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md-6">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
          <input type="text" id="gameSearch" class="form-control border-0 bg-light" placeholder="Buscar por nombre o categoría...">
        </div>
      </div>
      <div class="col-auto ms-auto">
        <span class="text-muted small fw-bold">Ordenar por: <b>Más recientes</b></span>
      </div>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
  <div class="table-responsive">
    <?php if (empty($items)): ?>
      <div class="py-5 text-center">
        <i class="bi bi-archive fs-1 text-muted opacity-25"></i>
        <p class="text-muted mt-2">Aún no hay productos en el catálogo.</p>
      </div>
    <?php else: ?>
      <table class="table table-games align-middle mb-0" id="gamesTable">
        <thead>
          <tr>
            <th class="ps-4">Imagen</th>
            <th>Juego / Producto</th>
            <th class="hide-mobile">Categoría</th>
            <th>Precio Base</th>
            <th class="hide-mobile">Medidas (L·A·H)</th>
            <th>Estado</th>
            <th class="text-end pe-4">Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $it):
            $img = !empty($it['cover_image']) ? ($bp . '/' . ltrim($it['cover_image'], '/')) : null;
            $pub = (int)$it['is_published'] === 1;
          ?>
            <tr>
              <td class="ps-4">
                <?php if ($img): ?>
                  <img src="<?= htmlspecialchars($img) ?>" class="game-thumb shadow-sm" alt="">
                <?php else: ?>
                  <div class="bg-light border game-thumb d-flex align-items-center justify-content-center">
                    <i class="bi bi-image text-muted opacity-50"></i>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <div class="game-title"><?= htmlspecialchars($it['nombre']) ?></div>
                <div class="game-slug"><?= htmlspecialchars($it['slug']) ?></div>
              </td>
              <td class="hide-mobile">
                <span class="badge-category"><?= htmlspecialchars($it['categoria'] ?? 'Sin categoría') ?></span>
              </td>
              <td>
                <div class="price-tag"><?= money((int)$it['precio_base']) ?></div>
              </td>
              <td class="hide-mobile">
                <div class="dim-tag">
                  <span class="dim-val"><?= (int)$it['largo_cm'] ?></span>
                  <span class="dim-val"><?= (int)$it['ancho_cm'] ?></span>
                  <span class="dim-val"><?= (int)$it['alto_cm'] ?></span>
                  <span class="ms-1 fw-bold">cm</span>
                </div>
              </td>
              <td>
                <?php if ($pub): ?>
                  <span class="badge bg-success bg-opacity-10 text-success fw-bold p-2 px-3 rounded-pill" style="font-size:0.65rem;">
                    <i class="bi bi-check-circle-fill me-1"></i> PUBLICADO
                  </span>
                <?php else: ?>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold p-2 px-3 rounded-pill" style="font-size:0.65rem;">
                    <i class="bi bi-pencil me-1"></i> BORRADOR
                  </span>
                <?php endif; ?>
              </td>
              <td class="text-end pe-4">
                <a class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" href="<?= $bp ?>/admin/games/edit/<?= (int)$it['id'] ?>">
                  Editar <i class="bi bi-chevron-right ms-1 small"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<script>
  // Filtro de búsqueda en tiempo real
  document.getElementById('gameSearch').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('#gamesTable tbody tr');
    rows.forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
  });
</script>