<?php

/** @var array $reservations */
/** @var array $summary */
/** @var string $date */
/** @var string $pay */

$bp = $basePath ?? '';

function money(int $v): string
{
    return '$' . number_format($v, 0, ',', '.');
}

function payBadge(int $psId): array
{
    if ($psId === 3) return ['Pagado', 'bg-success'];
    if ($psId === 2) return ['Abonado', 'bg-info text-dark'];
    if ($psId === 4) return ['Devuelto', 'bg-dark text-white'];
    return ['Pendiente', 'bg-warning text-dark'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<style>
    .route-card {
        cursor: grab;
        transition: all 0.2s;
        border-left: 6px solid #0d6efd;
    }

    .route-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .route-card:active {
        cursor: grabbing;
    }

    .sortable-ghost {
        opacity: 0.3;
        background-color: #e9ecef !important;
        border: 2px dashed #0d6efd !important;
    }

    .comuna-badge {
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .game-tag {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 2px 8px;
        font-size: 0.8rem;
        display: inline-block;
        font-weight: 600;
    }
</style>

<div class="row align-items-center mb-4">
    <div class="col">
        <h1 class="h3 mb-1 fw-bold">Ruta de Entregas</h1>
        <p class="text-muted mb-0 small"><i class="bi bi-calendar-event me-1"></i> Programación para el <b><?= htmlspecialchars($date) ?></b></p>
    </div>
    <div class="col-auto d-flex gap-2">
        <button class="btn btn-outline-success btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i> Imprimir</button>
        <a class="btn btn-primary btn-sm" href="<?= $bp ?>/admin/reservations/create">+ Nueva Reserva</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small fw-bold text-uppercase">Entregas</div>
                <div class="h4 mb-0"><?= $summary['count'] ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3">
                <div class="text-muted small fw-bold text-uppercase">Por Cobrar</div>
                <div class="h4 mb-0 text-danger"><?= money($summary['pending']) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="get" class="row g-2 align-items-center">
            <div class="col-auto">
                <input type="date" name="date" class="form-control form-control-sm" value="<?= $date ?>">
            </div>
            <div class="col-auto">
                <select name="pay" class="form-select form-select-sm">
                    <option value="all">Todos los pagos</option>
                    <option value="pending" <?= $pay === 'pending' ? 'selected' : '' ?>>Pendientes</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-dark btn-sm px-3">Aplicar</button>
            </div>
        </form>
    </div>
</div>

<div id="route-list" class="d-flex flex-column gap-3 mb-5">
    <?php if (empty($reservations)): ?>
        <div class="text-center py-5 bg-light rounded-4 border">
            <i class="bi bi-box-seam fs-1 text-muted"></i>
            <p class="text-muted mt-2">No hay rutas confirmadas para este filtro.</p>
        </div>
        <?php else: foreach ($reservations as $index => $r):
            [$pLabel, $pBadge] = payBadge((int)$r['payment_status_id']);
            $pend = (int)$r['total_amount'] - (int)$r['paid_amount'];
        ?>
            <div class="card route-card border-0 shadow-sm" data-id="<?= $r['id'] ?>">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-auto text-center border-end pe-3">
                            <span class="text-muted small fw-bold">#</span>
                            <div class="h4 mb-0 fw-black text-primary route-number"><?= $index + 1 ?></div>
                            <i class="bi bi-grip-vertical fs-5 text-muted"></i>
                        </div>

                        <div class="col-md-4 px-4">
                            <span class="badge bg-dark comuna-badge mb-2 rounded-pill px-3 py-1">
                                <i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($r['comuna']) ?>
                            </span>
                            <div class="fw-bold fs-5 text-dark lh-sm"><?= htmlspecialchars($r['direccion']) ?></div>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-clock me-1"></i> <?= $r['start_time'] ? substr((string)$r['start_time'], 0, 5) : 'Sin hora' ?> - <?= $r['end_time'] ? substr((string)$r['end_time'], 0, 5) : '' ?>
                            </div>
                        </div>

                        <div class="col-md-4 px-3 border-start">
                            <div class="text-muted small fw-bold mb-1 text-uppercase">Carga:</div>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach (explode(' | ', $r['games_list']) as $game): ?>
                                    <span class="game-tag"><?= htmlspecialchars($game) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-2 d-flex align-items-center">
                                <div class="small fw-bold me-2"><?= htmlspecialchars($r['customer_name']) ?></div>
                                <a href="https://wa.me/<?= preg_replace('/\D/', '', $r['customer_phone']) ?>" target="_blank" class="btn btn-sm btn-success py-0 px-2 rounded-pill">
                                    <i class="bi bi-whatsapp small"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col text-end border-start">
                            <div class="small text-muted">A Cobrar:</div>
                            <div class="fw-bold <?= $pend > 0 ? 'text-danger' : 'text-success' ?> fs-5"><?= money($pend) ?></div>
                            <span class="badge <?= $pBadge ?> rounded-pill"><?= $pLabel ?></span>
                            <div class="mt-2">
                                <a href="<?= $bp ?>/admin/reservations/<?= $r['id'] ?>" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold">Detalles →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php endforeach;
    endif; ?>
</div>

<script>
    // Activamos el Drag & Drop
    const routeList = document.getElementById('route-list');
    if (routeList) {
        Sortable.create(routeList, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            onEnd: function() {
                // Re-enumerar visualmente las tarjetas al soltarlas
                document.querySelectorAll('.route-number').forEach((el, i) => {
                    el.textContent = i + 1;
                });
                console.log("Ruta reordenada localmente.");
            }
        });
    }
</script>