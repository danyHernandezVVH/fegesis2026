<?php

/** @var array $categorias */
/** @var string $csrf */
/** @var string $status */
$bp = ($basePath ?? '');

// Conteos rápidos para KPIs
$activeCount = 0;
$archivedCount = 0;
foreach ($categorias as $c) {
  if ((int)$c['is_active'] === 1) $activeCount++;
  else $archivedCount++;
}
?>

<style>
  :root {
    --fegesis-primary: #0d6efd;
    --fegesis-gray: #64748b;
  }

  .kpi-card-cat {
    border: none;
    border-radius: 12px;
    transition: transform 0.2s;
    background: #fff;
  }

  .kpi-card-cat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Filtros Estilo Pill */
  .filter-pill {
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.5rem 1.2rem;
    border: 1px solid #e2e8f0;
    color: var(--fegesis-gray);
    text-decoration: none;
    transition: all 0.2s;
  }

  .filter-pill:hover,
  .filter-pill.active {
    background: var(--fegesis-primary);
    color: white;
    border-color: var(--fegesis-primary);
  }

  /* Tabla Compacta */
  .table-cat thead th {
    background: #f8fafc;
    padding: 12px 15px;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--fegesis-gray);
    border-bottom: 2px solid #edf2f7;
  }

  .table-cat tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
  }

  .table-cat tbody tr:hover {
    background-color: #f8fbff;
  }

  .badge-soft-success {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
  }

  .badge-soft-secondary {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.2);
  }

  .btn-action-sm {
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
    padding: 4px 12px;
  }
</style>

<div class="row align-items-center mb-4">
  <div class="col">
    <h1 class="h3 mb-1 fw-bold">Gestión de Categorías</h1>
    <p class="text-muted small mb-0"><i class="bi bi-tag-fill me-1"></i> Organiza el catálogo de productos por familias y orden de visualización.</p>
  </div>
  <div class="col-auto">
    <a class="btn btn-primary fw-bold shadow-sm px-4 rounded-3" href="<?= $bp ?>/admin/categories/create">
      <i class="bi bi-plus-lg me-1"></i> Nueva Categoría
    </a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-4">
    <div class="card kpi-card-cat shadow-sm">
      <div class="card-body py-3 d-flex align-items-center">
        <div class="icon-box bg-primary bg-opacity-10 text-primary me-3"><i class="bi bi-check-circle fs-4"></i></div>
        <div>
          <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Activas</div>
          <div class="h4 mb-0 fw-bold"><?= $activeCount ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-md-4">
    <div class="card kpi-card-cat shadow-sm">
      <div class="card-body py-3 d-flex align-items-center">
        <div class="icon-box bg-secondary bg-opacity-10 text-secondary me-3"><i class="bi bi-archive fs-4"></i></div>
        <div>
          <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Archivadas</div>
          <div class="h4 mb-0 fw-bold"><?= $archivedCount ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="card kpi-card-cat shadow-sm">
      <div class="card-body py-3 d-flex align-items-center">
        <div class="icon-box bg-dark bg-opacity-10 text-dark me-3"><i class="bi bi-list-task fs-4"></i></div>
        <div>
          <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;">Total Registros</div>
          <div class="h4 mb-0 fw-bold"><?= count($categorias) ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
  <a class="filter-pill <?= ($status === 'active') ? 'active' : '' ?>" href="<?= $bp ?>/admin/categories?status=active">Activas</a>
  <a class="filter-pill <?= ($status === 'inactive') ? 'active' : '' ?>" href="<?= $bp ?>/admin/categories?status=inactive">Inactivas (Archivadas)</a>
  <a class="filter-pill <?= ($status === 'all') ? 'active' : '' ?>" href="<?= $bp ?>/admin/categories?status=all">Todas</a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
  <div class="table-responsive">
    <?php if (empty($categorias)): ?>
      <div class="py-5 text-center">
        <i class="bi bi-tag fs-1 text-muted opacity-25"></i>
        <p class="text-muted mt-2">No hay categorías que coincidan con el filtro.</p>
      </div>
    <?php else: ?>
      <table class="table table-cat align-middle mb-0">
        <thead>
          <tr>
            <th class="ps-4">Nombre / Slug</th>
            <th class="text-center">Orden</th>
            <th class="text-center">Estado</th>
            <th class="text-end pe-4">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categorias as $c):
            $isActive = (int)$c['is_active'] === 1;
          ?>
            <tr>
              <td class="ps-4">
                <div class="fw-bold text-dark"><?= htmlspecialchars($c['nombre']) ?></div>
                <div class="text-muted extra-small" style="font-size: 0.75rem;">/<?= htmlspecialchars($c['slug']) ?></div>
              </td>
              <td class="text-center">
                <span class="badge bg-light text-dark border px-3 py-1"><?= (int)$c['orden'] ?></span>
              </td>
              <td class="text-center">
                <?php if ($isActive): ?>
                  <span class="badge badge-soft-success px-3 py-2 rounded-pill"><i class="bi bi-check2 me-1"></i> ACTIVA</span>
                <?php else: ?>
                  <span class="badge badge-soft-secondary px-3 py-2 rounded-pill"><i class="bi bi-archive me-1"></i> INACTIVA</span>
                <?php endif; ?>
              </td>
              <td class="text-end pe-4">
                <div class="d-flex justify-content-end gap-2">
                  <a class="btn btn-action-sm btn-outline-primary" href="<?= $bp ?>/admin/categories/edit/<?= (int)$c['id'] ?>">
                    <i class="bi bi-pencil me-1"></i> Editar
                  </a>

                  <form method="post" action="<?= $bp ?>/admin/categories/toggle/<?= (int)$c['id'] ?>" onsubmit="return confirm('¿Cambiar estado?');">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
                    <button class="btn btn-action-sm <?= $isActive ? 'btn-outline-danger' : 'btn-outline-success' ?>" type="submit">
                      <?= $isActive ? '<i class="bi bi-archive me-1"></i> Archivar' : '<i class="bi bi-arrow-up-circle me-1"></i> Reactivar' ?>
                    </button>
                  </form>

                  <?php if (!$isActive && $status === 'inactive'): ?>
                    <form method="post" action="<?= $bp ?>/admin/categories/delete/<?= (int)$c['id'] ?>" onsubmit="return confirm('⚠️ ¡Atención! Se eliminará definitivamente.');">
                      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">
                      <button class="btn btn-action-sm btn-danger" type="submit"><i class="bi bi-trash3"></i></button>
                    </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>

<div class="mt-4 text-muted small p-2 bg-light rounded-3 border-start border-primary border-4">
  <i class="bi bi-info-circle me-1"></i> <strong>Nota:</strong> Solo podrás eliminar definitivamente categorías archivadas que <strong>no tengan juegos asociados</strong>. Si tienen productos, el sistema protegerá la integridad del catálogo bloqueando la eliminación.
</div>