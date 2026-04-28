<?php

/** @var string $today */
/** @var array $kpiToday */
/** @var int $pendingConfirmCount */
/** @var int $unpaidConfirmedCount */
/** @var array $pendingList */
/** @var array $unpaidList */
/** @var array $next7List */
/** @var array $topGames */
/** @var array $payLabels */

$bp = $basePath ?? '';

function money(int $v): string
{
  return '$' . number_format($v, 0, ',', '.');
}

// KPI Today Data
$cntToday     = (int)($kpiToday['cnt'] ?? 0);
$totalToday   = (int)($kpiToday['total_amount'] ?? 0);
$paidToday    = (int)($kpiToday['paid_amount'] ?? 0);
$pendingToday = (int)($kpiToday['pending_amount'] ?? 0);

$opsUrl = $bp . '/admin/operations?date=' . urlencode($today);
?>

<style>
  .card {
    border-radius: 12px;
    border: 1px solid #edf2f9;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  .icon-shape {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
  }

  .bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
  }

  .bg-soft-success {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
  }

  .bg-soft-warning {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
  }

  .bg-soft-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
  }

  .table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #6e84a3;
    background-color: #f9fbfd;
    border-top: none;
  }
</style>

<div class="row align-items-center mb-4">
  <div class="col">
    <h1 class="h3 mb-0 fw-bold">Dashboard</h1>
    <p class="text-muted mb-0 small"><i class="bi bi-calendar3 me-1"></i> Resumen operacional para hoy, <?= htmlspecialchars($today) ?></p>
  </div>
  <div class="col-auto d-flex gap-2">
    <a class="btn btn-white btn-sm shadow-sm border" href="<?= $bp ?>/admin/reservations/create"><i class="bi bi-plus-lg me-1"></i> Nueva Reserva</a>
    <a class="btn btn-primary btn-sm shadow-sm" href="<?= htmlspecialchars($opsUrl) ?>"><i class="bi bi-truck me-1"></i> Operación del Día</a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-12 col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <h6 class="text-uppercase text-muted fw-bold small mb-2">Confirmadas Hoy</h6>
            <span class="h3 mb-0 fw-bold text-dark"><?= $cntToday ?></span>
          </div>
          <div class="col-auto">
            <div class="icon-shape bg-soft-primary"><i class="bi bi-check2-circle fs-4"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <h6 class="text-uppercase text-muted fw-bold small mb-2">Total Ventas</h6>
            <span class="h3 mb-0 fw-bold text-dark"><?= money($totalToday) ?></span>
          </div>
          <div class="col-auto">
            <div class="icon-shape bg-soft-success"><i class="bi bi-graph-up-arrow fs-4"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <h6 class="text-uppercase text-muted fw-bold small mb-2">Recaudado</h6>
            <span class="h3 mb-0 fw-bold text-dark"><?= money($paidToday) ?></span>
          </div>
          <div class="col-auto">
            <div class="icon-shape bg-soft-warning"><i class="bi bi-cash-stack fs-4"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-3">
    <div class="card h-100">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col">
            <h6 class="text-uppercase text-muted fw-bold small mb-2">Por Cobrar</h6>
            <span class="h3 mb-0 fw-bold text-danger"><?= money($pendingToday) ?></span>
          </div>
          <div class="col-auto">
            <div class="icon-shape bg-soft-danger"><i class="bi bi-wallet2 fs-4"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-12 col-lg-6">
    <div class="card border-0 border-start border-warning border-4 shadow-sm bg-white">
      <div class="card-body py-3">
        <div class="d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="fw-bold text-dark mb-0">Pendientes de Confirmación</div>
            <div class="text-muted small">Hay <?= (int)$pendingConfirmCount ?> solicitudes esperando tu respuesta.</div>
          </div>
          <a href="<?= $bp ?>/admin/reservations?status=pending" class="btn btn-warning btn-sm fw-bold px-3">Atender</a>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-6">
    <div class="card border-0 border-start border-info border-4 shadow-sm bg-white">
      <div class="card-body py-3">
        <div class="d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="fw-bold text-dark mb-0">Confirmadas no Pagadas</div>
            <div class="text-muted small"><?= (int)$unpaidConfirmedCount ?> servicios confirmados sin pago total.</div>
          </div>
          <a href="<?= $bp ?>/admin/reservations?status=confirmed&pay=pending" class="btn btn-info btn-sm fw-bold px-3">Ver Pagos</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-12 col-lg-7">
    <div class="card shadow-none border-0 h-100">
      <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-warning"></i> Próximas por Confirmar</h5>
        <a class="btn btn-sm btn-link text-decoration-none p-0" href="<?= $bp ?>/admin/reservations?status=pending">Ver todas <i class="bi bi-chevron-right small"></i></a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Juego / Comuna</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($pendingList)): ?>
                <tr>
                  <td colspan="5" class="text-center py-4 text-muted fst-italic">No hay pendientes por ahora.</td>
                </tr>
                <?php else: foreach ($pendingList as $r): ?>
                  <tr>
                    <td class="small fw-bold">#<?= $r['id'] ?></td>
                    <td>
                      <div class="fw-bold text-dark"><?= htmlspecialchars($r['games']) ?></div>
                      <div class="text-muted small text-uppercase" style="font-size: 0.65rem;"><?= htmlspecialchars($r['comuna'] ?? '') ?></div>
                    </td>
                    <td class="small fw-semibold"><?= htmlspecialchars($r['event_date']) ?></td>
                    <td>
                      <div class="fw-semibold small"><?= htmlspecialchars($r['customer_name']) ?></div>
                      <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($r['customer_phone']) ?></div>
                    </td>
                    <td class="text-end pe-3">
                      <a class="btn btn-light btn-sm border" href="<?= $bp ?>/admin/reservations/<?= $r['id'] ?>">Revisar</a>
                    </td>
                  </tr>
              <?php endforeach;
              endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-5">
    <div class="card h-100">
      <div class="card-header bg-white py-3 border-bottom">
        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-stars me-2 text-primary"></i> Rendimiento Mensual</h5>
      </div>
      <div class="card-body">
        <?php if (empty($topGames)): ?>
          <p class="text-muted">Sin datos suficientes este mes.</p>
          <?php else:
          $maxVal = (int)($topGames[0]['reservas'] ?? 1);
          foreach ($topGames as $g):
            $width = ($maxVal > 0) ? ($g['reservas'] / $maxVal) * 100 : 0;
          ?>
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-bold text-dark small"><?= htmlspecialchars($g['game_name']) ?></span>
                <span class="small fw-bold text-muted"><?= (int)$g['reservas'] ?> Res.</span>
              </div>
              <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $width ?>%"></div>
              </div>
              <div class="text-end mt-1 fw-semibold text-success" style="font-size: 0.7rem;">+ <?= money((int)$g['total_amount']) ?></div>
            </div>
        <?php endforeach;
        endif; ?>
      </div>
    </div>
  </div>
</div>