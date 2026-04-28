<?php

/** @var array $reservas */
/** @var array $stats */
/** @var int $totalRows */
$bp = $basePath ?? '';

function money(int $v): string
{
    return '$' . number_format($v, 0, ',', '.');
}

function getStatusConfig(int $sid): array
{
    return match ($sid) {
        2 => ['label' => 'Confirmada', 'class' => 'bg-success', 'icon' => 'bi-check-circle-fill'],
        3 => ['label' => 'Cancelada', 'class' => 'bg-secondary', 'icon' => 'bi-x-circle'],
        4 => ['label' => 'Rechazada', 'class' => 'bg-danger', 'icon' => 'bi-slash-circle'],
        default => ['label' => 'Pendiente', 'class' => 'bg-warning text-dark', 'icon' => 'bi-clock-history'],
    };
}
?>

<style>
    /* Estética de KPIs */
    .kpi-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .kpi-card {
        background: #fff;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        transition: transform 0.2s;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
    }

    /* Distribución de Tabla Uniforme */
    .table-main {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
    }

    .table-main thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        padding: 15px;
        border-bottom: 2px solid #edf2f7;
        font-weight: 800;
    }

    .table-main td {
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-main tbody tr:hover {
        background-color: #f8fbff;
    }

    /* Control de Columnas para evitar el gran espacio */
    .col-id {
        width: 80px;
    }

    .col-game {
        width: 30%;
    }

    .col-client {
        width: 25%;
    }

    .col-status {
        width: 15%;
    }

    .col-amount {
        width: 15%;
    }

    .col-action {
        width: 80px;
    }

    /* Tipografías y Elementos */
    .game-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
        display: block;
    }

    .text-small {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .status-pill {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 6px 12px;
        border-radius: 8px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .pay-indicator {
        height: 5px;
        border-radius: 10px;
        background: #e2e8f0;
        width: 60px;
        margin-top: 6px;
        overflow: hidden;
    }

    /* Responsividad */
    @media (max-width: 992px) {
        .col-client {
            width: 20%;
        }

        .col-game {
            width: 35%;
        }
    }

    @media (max-width: 768px) {
        .hide-mobile {
            display: none;
        }

        .col-game {
            width: 50%;
        }

        .table-main td {
            padding: 10px 8px;
        }
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h1 class="h3 mb-0 fw-bold text-dark">Gestión de Reservas</h1>
        <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Mostrando <?= count($reservas) ?> de <?= $totalRows ?> registros totales.</p>
    </div>
    <a href="<?= $bp ?>/admin/reservations/create" class="btn btn-primary fw-bold shadow-sm px-4">
        <i class="bi bi-plus-lg me-1"></i> Nueva Reserva
    </a>
</div>

<div class="kpi-wrapper">
    <?php
    $kpiList = [
        ['l' => 'POR CONFIRMAR', 'v' => $stats['pending'], 'i' => 'bi-clock', 'c' => 'warning'],
        ['l' => 'CONFIRMADAS', 'v' => $stats['confirmed'], 'i' => 'bi-shield-check', 'c' => 'success'],
        ['l' => 'IMPAGAS', 'v' => $stats['unpaid'], 'i' => 'bi-exclamation-triangle', 'c' => 'danger'],
        ['l' => 'PAGADAS', 'v' => $stats['paid'], 'i' => 'bi-cash-coin', 'c' => 'primary']
    ];
    foreach ($kpiList as $k): ?>
        <div class="kpi-card shadow-sm border-0">
            <div class="icon-box bg-<?= $k['c'] ?> bg-opacity-10 p-2 me-3 rounded-3 text-<?= $k['c'] ?>">
                <i class="<?= $k['i'] ?> fs-4"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.6rem;"><?= $k['l'] ?></div>
                <div class="h4 mb-0 fw-bold"><?= $k['v'] ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="get" class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" class="form-control border-0 bg-light" placeholder="Búsqueda rápida...">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm border-0 bg-light fw-bold">
                    <option value="all">Todos los Estados</option>
                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pendientes</option>
                    <option value="confirmed" <?= $status === 'confirmed' ? 'selected' : '' ?>>Confirmadas</option>
                </select>
            </div>
            <div class="col-6 col-md-4">
                <div class="d-flex gap-1">
                    <input type="date" name="from" value="<?= $from ?>" class="form-control form-control-sm border-0 bg-light">
                    <input type="date" name="to" value="<?= $to ?>" class="form-control form-control-sm border-0 bg-light">
                </div>
            </div>
            <div class="col-12 col-md-auto ms-auto">
                <button type="submit" class="btn btn-dark btn-sm w-100 px-4 fw-bold">Filtrar</button>
            </div>
        </form>
        <div class="d-flex flex-wrap gap-2 mt-2 pt-2 border-top">
            <a href="?status=pending" class="filter-pill btn btn-sm btn-outline-secondary py-1 px-3 rounded-pill <?= $status === 'pending' ? 'active bg-primary text-white border-primary' : '' ?>" style="font-size: 0.75rem;">Pendientes de Confirmar</a>
            <a href="?pay=pending" class="filter-pill btn btn-sm btn-outline-secondary py-1 px-3 rounded-pill" style="font-size: 0.75rem;">Pagos Pendientes</a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table-main">
            <thead>
                <tr>
                    <th class="ps-4 col-id">ID</th>
                    <th class="col-game">Juego & Ubicación</th>
                    <th class="col-client hide-mobile">Cliente</th>
                    <th class="col-status">Estado de Gestión</th>
                    <th class="col-amount text-end">Monto Total</th>
                    <th class="text-center pe-4 col-action">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $r):
                    $sid = (int)$r['status_id'];
                    $conf = getStatusConfig($sid);
                    $total = (int)$r['total_amount'];
                    $paid = (int)$r['paid_amount'];
                    $perc = ($total > 0) ? ($paid / $total) * 100 : 0;
                ?>
                    <tr>
                        <td class="ps-4 col-id">
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">#<?= $r['id'] ?></div>
                            <div class="text-small fw-bold"><?= $r['event_date'] ?></div>
                        </td>
                        <td class="col-game">
                            <span class="game-name text-truncate" style="max-width: 250px;"><?= htmlspecialchars($r['game_name']) ?></span>
                            <div class="text-small fw-bold text-muted"><i class="bi bi-geo-alt-fill me-1 text-primary"></i><?= htmlspecialchars($r['comuna']) ?></div>
                        </td>
                        <td class="col-client hide-mobile">
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($r['customer_name']) ?></div>
                            <div class="text-success small fw-bold"><i class="bi bi-whatsapp me-1"></i><?= htmlspecialchars($r['customer_phone']) ?></div>
                        </td>
                        <td class="col-status">
                            <span class="status-pill <?= $conf['class'] ?>"><i class="bi <?= $conf['icon'] ?>"></i> <?= $conf['label'] ?></span>
                            <div class="pay-indicator shadow-sm">
                                <div class="progress-bar <?= $perc >= 100 ? 'bg-success' : 'bg-warning' ?>" style="width: <?= $perc ?>%; height: 100%;"></div>
                            </div>
                        </td>
                        <td class="col-amount text-end">
                            <div class="fw-bold text-dark fs-6"><?= money($total) ?></div>
                            <div class="text-danger fw-bold" style="font-size: 0.65rem;">S: <?= money($total - $paid) ?></div>
                        </td>
                        <td class="text-center pe-4 col-action">
                            <a href="<?= $bp ?>/admin/reservations/<?= $r['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill p-1 px-3 shadow-sm fw-bold" style="font-size: 0.75rem;">
                                Gestionar <i class="bi bi-chevron-right small"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>