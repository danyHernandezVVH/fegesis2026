<?php

/** @var array $reserva */
/** @var string $csrf */
/** @var array $payLabels */
/** @var array $reservationItems */ // Array con los juegos de esta reserva
/** @var array $chatMessages */     // Array con el historial de mensajes

$bp = $basePath ?? '';

function money(int $v): string
{
    return '$' . number_format($v, 0, ',', '.');
}

// --------------------
// Datos base
// --------------------
$resId      = (int)($reserva['id'] ?? 0);
$statusId   = (int)($reserva['status_id'] ?? 1);
$statusLabel = (string)($reserva['status_label'] ?? '—');

$mensaje = (string)($reserva['mensaje'] ?? '');

$customerName  = trim((string)($reserva['customer_name'] ?? '')) ?: '—';
$customerEmail = trim((string)($reserva['customer_email'] ?? '')) ?: '—';
$customerPhone = trim((string)($reserva['customer_phone'] ?? '')) ?: '—';

$createdAt = (string)($reserva['created_at'] ?? '—');
$eventDate = (string)($reserva['event_date'] ?? '—');
$direccion = (string)($reserva['direccion'] ?? '');
$comuna    = (string)($reserva['comuna'] ?? '');

// --------------------
// Configuración Visual del Estado de la Reserva
// --------------------
$statusBadge = 'bg-secondary';
$statusIcon  = 'bi-circle';
if ($statusId === 1) {
    $statusBadge = 'bg-warning text-dark';
    $statusIcon = 'bi-clock-history';
}
if ($statusId === 2) {
    $statusBadge = 'bg-success';
    $statusIcon = 'bi-check-circle-fill';
}
if ($statusId === 3) {
    $statusBadge = 'bg-secondary';
    $statusIcon = 'bi-x-circle-fill';
}
if ($statusId === 4) {
    $statusBadge = 'bg-danger';
    $statusIcon = 'bi-slash-circle-fill';
}

// --------------------
// Pago
// --------------------
$total  = (int)($reserva['total_amount'] ?? 0);
$paid   = (int)($reserva['paid_amount'] ?? 0);
$remain = max(0, $total - $paid);

$psId    = (int)($reserva['payment_status_id'] ?? 1);
$psLabel = $payLabels[$psId] ?? '—';

$psBadge = 'bg-secondary';
if ($psId === 1) $psBadge = 'bg-warning text-dark'; // Pendiente
if ($psId === 2) $psBadge = 'bg-info text-dark';    // Abonado
if ($psId === 3) $psBadge = 'bg-success';           // Pagado
if ($psId === 4) $psBadge = 'bg-dark';              // Devuelto

$canPay      = ($total > 0);
$isPaid      = ($psId === 3 || $paid >= $total);
$isRefunded  = ($psId === 4);

// --------------------
// Reglas de UI
// --------------------
$isPending   = ($statusId === 1);
$isConfirmed = ($statusId === 2);
$isCancelled = ($statusId === 3);
$isRejected  = ($statusId === 4);

$canConfirm = $isPending;
$canReject  = $isPending;
$canCancel  = $isPending || $isConfirmed;

$statusHint = '';
if ($isConfirmed) $statusHint = 'Reserva confirmada. Acciones de aprobación bloqueadas.';
if ($isCancelled) $statusHint = 'Reserva cancelada. Acciones bloqueadas.';
if ($isRejected)  $statusHint = 'Reserva rechazada. Acciones bloqueadas.';

$paymentHint = '';
if (!$canPay) $paymentHint = 'Reserva sin monto total configurado.';
if ($isRefunded) $paymentHint = 'Marcado como “Devuelto”. Abonos bloqueados.';
?>

<style>
    /* Estilos del Chat */
    .chat-box {
        max-height: 350px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
    }

    .chat-bubble {
        max-width: 85%;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Ajuste visual para la lista de juegos */
    .game-thumb {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 12px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1 fw-bold text-dark">
            Reserva <span class="text-primary">#<?= $resId ?></span>
        </h1>
        <span class="text-muted small"><i class="bi bi-calendar-plus"></i> Ingresada el: <?= htmlspecialchars($createdAt) ?></span>
    </div>
    <div>
        <span class="badge <?= $statusBadge ?> fs-6 px-3 py-2 rounded-pill shadow-sm">
            <i class="bi <?= $statusIcon ?> me-1"></i> <?= htmlspecialchars($statusLabel) ?>
        </span>
    </div>
</div>

<?php if (!empty($_SESSION['_flash_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars((string)$_SESSION['_flash_success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['_flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['_flash_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars((string)$_SESSION['_flash_error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['_flash_error']); ?>
<?php endif; ?>

<div class="row g-4">

    <div class="col-12 col-xl-7">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam text-primary me-2"></i> Detalles del Evento</h5>
            </div>
            <div class="card-body p-4">

                <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Juegos Reservados</h6>
                <div class="d-flex flex-column gap-2 mb-4">
                    <?php if (!empty($reservationItems)): ?>
                        <?php foreach ($reservationItems as $item): ?>
                            <div class="d-flex align-items-center p-2 bg-light rounded-4 border border-light">
                                <img src="<?= $bp ?>/<?= htmlspecialchars($item['image'] ?? 'img/default.jpg') ?>"
                                    class="game-thumb shadow-sm border"
                                    alt="<?= htmlspecialchars($item['game_name'] ?? 'Juego') ?>"
                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($item['game_name'] ?? 'J') ?>&background=f1f5f9&color=475569&size=128&font-size=0.4&bold=true';">
                                <div class="ms-3 flex-grow-1">
                                    <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($item['game_name'] ?? 'Juego no definido') ?></div>
                                    <div class="text-muted small">Cantidad requerida: <span class="badge bg-secondary rounded-pill"><?= (int)($item['qty'] ?? 1) ?></span></div>
                                </div>
                                <div class="text-end px-3">
                                    <div class="text-muted small">Precio Unit.</div>
                                    <div class="fw-bold text-primary"><?= money((int)($item['unit_price'] ?? 0)) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning py-2 small border-0 bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-exclamation-circle"></i> Faltan cargar los juegos de esta reserva desde la base de datos.
                        </div>
                    <?php endif; ?>
                </div>

                <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Logística</h6>
                <div class="row g-3 mb-4">
                    <div class="col-sm-5">
                        <div class="p-3 bg-light rounded-4 h-100">
                            <div class="text-muted small mb-1"><i class="bi bi-calendar-event"></i> Fecha del Evento</div>
                            <div class="fw-bold text-dark fs-5"><?= htmlspecialchars($eventDate) ?></div>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="p-3 bg-light rounded-4 h-100">
                            <div class="text-muted small mb-1"><i class="bi bi-geo-alt"></i> Dirección Exacta</div>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($direccion) ?></div>
                            <?php if ($comuna !== ''): ?><div class="text-muted small"><?= htmlspecialchars($comuna) ?></div><?php endif; ?>
                        </div>
                    </div>
                </div>

                <h6 class="text-uppercase text-muted fw-bold mb-3 small" style="letter-spacing: 1px;">Contacto del Cliente</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 border rounded-4 text-center h-100">
                            <div class="text-muted small mb-1"><i class="bi bi-person fs-5"></i></div>
                            <div class="fw-semibold text-truncate" title="<?= htmlspecialchars($customerName) ?>"><?= htmlspecialchars($customerName) ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-4 text-center h-100">
                            <div class="text-muted small mb-1"><i class="bi bi-envelope fs-5"></i></div>
                            <div class="fw-semibold text-truncate" title="<?= htmlspecialchars($customerEmail) ?>">
                                <a href="mailto:<?= htmlspecialchars($customerEmail) ?>" class="text-decoration-none"><?= htmlspecialchars($customerEmail) ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-4 text-center h-100">
                            <div class="text-muted small mb-1"><i class="bi bi-whatsapp fs-5 text-success"></i></div>
                            <div class="fw-semibold">
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $customerPhone) ?>" target="_blank" class="text-decoration-none text-success"><?= htmlspecialchars($customerPhone) ?></a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-chat-square-dots text-info me-2"></i> Mensajería de la Reserva</h5>
                <p class="text-muted small mt-1 mb-0">Comunícate con el cliente sobre logística o dudas.</p>
            </div>
            <div class="card-body p-4">

                <div class="chat-box bg-light rounded-4 p-3 mb-3 border">

                    <?php if ($mensaje !== ''): ?>
                        <div class="mb-3">
                            <div class="small text-muted mb-1 ms-1">
                                <i class="bi bi-person-circle text-secondary"></i> Cliente <span class="fw-light ms-1">En el Checkout</span>
                            </div>
                            <div class="p-3 rounded-4 shadow-sm d-inline-block bg-white text-dark chat-bubble border-start border-warning border-4">
                                <div class="badge bg-warning text-dark mb-2"><i class="bi bi-pin-angle-fill"></i> Nota de Reserva</div><br>
                                <?= nl2br(htmlspecialchars($mensaje)) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($chatMessages)): ?>
                        <?php foreach ($chatMessages as $msg): ?>
                            <?php
                            // Ahora comparamos contra tu ENUM 'admin'
                            $isAdmin = ($msg['sender'] === 'admin');
                            ?>
                            <div class="mb-3 <?= $isAdmin ? 'text-end' : '' ?>">
                                <div class="small text-muted mb-1 <?= $isAdmin ? 'me-1' : 'ms-1' ?>">
                                    <?php if ($isAdmin): ?>
                                        <span class="fw-light me-1"><?= htmlspecialchars($msg['created_at']) ?></span> Fegesis <i class="bi bi-shield-lock-fill text-primary"></i>
                                    <?php else: ?>
                                        <i class="bi bi-person-circle text-secondary"></i> Cliente <span class="fw-light ms-1"><?= htmlspecialchars($msg['created_at']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-3 rounded-4 shadow-sm d-inline-block chat-bubble <?= $isAdmin ? 'bg-primary text-white text-start' : 'bg-white text-dark border' ?>">
                                    <?= nl2br(htmlspecialchars($msg['message'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <form method="post" action="<?= $bp ?>/admin/reservations/<?= $resId ?>/messages" class="js-lock-on-submit">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string)$csrf) ?>">
                    <div class="input-group shadow-sm rounded-4 overflow-hidden">
                        <input type="text" name="message" class="form-control border-0 bg-light p-3" placeholder="Escribe tu respuesta aquí..." required autocomplete="off">
                        <button class="btn btn-info text-white fw-bold px-4 border-0" type="submit">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-toggle-on text-secondary me-2"></i> Gestión de Estado</h5>
                    <?php if ($statusHint !== ''): ?>
                        <span class="badge bg-light text-secondary border"><i class="bi bi-info-circle"></i> <?= htmlspecialchars($statusHint) ?></span>
                    <?php endif; ?>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <form method="post" action="<?= $bp ?>/admin/reservations/<?= $resId ?>/status" class="js-lock-on-submit">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string)$csrf) ?>">
                        <input type="hidden" name="status_id" value="2">
                        <button type="submit" class="btn btn-success fw-semibold px-4" <?= $canConfirm ? '' : 'disabled' ?> onclick="return confirm('¿Confirmar esta reserva y notificar al cliente?')">
                            <i class="bi bi-check-lg"></i> Confirmar
                        </button>
                    </form>

                    <form method="post" action="<?= $bp ?>/admin/reservations/<?= $resId ?>/status" class="js-lock-on-submit">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string)$csrf) ?>">
                        <input type="hidden" name="status_id" value="4">
                        <button type="submit" class="btn btn-outline-danger fw-semibold px-4" <?= $canReject ? '' : 'disabled' ?> onclick="return confirm('¿Rechazar esta solicitud?')">
                            <i class="bi bi-x-lg"></i> Rechazar
                        </button>
                    </form>

                    <form method="post" action="<?= $bp ?>/admin/reservations/<?= $resId ?>/status" class="js-lock-on-submit ms-auto">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string)$csrf) ?>">
                        <input type="hidden" name="status_id" value="3">
                        <button type="submit" class="btn btn-secondary fw-semibold px-4" <?= $canCancel ? '' : 'disabled' ?> onclick="return confirm('¿Cancelar esta reserva? (Acción irreversible)')">
                            <i class="bi bi-slash-circle"></i> Cancelar Reserva
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="col-12 col-xl-5">

        <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top" style="top: 80px; z-index: 1;">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-wallet2 text-success me-2"></i> Control de Pagos</h5>
                <p class="text-muted small mt-1 mb-0">Gestión interna (No procesa cobros reales)</p>
            </div>

            <div class="card-body p-4">

                <div class="bg-dark text-white p-4 rounded-4 mb-4 text-center position-relative overflow-hidden shadow">
                    <div class="position-absolute top-0 end-0 opacity-25 p-3 fs-1"><i class="bi bi-cash-stack"></i></div>
                    <div class="text-uppercase small fw-bold text-white-50 mb-1">Monto Total</div>
                    <div class="display-5 fw-bold mb-0"><?= money($total) ?></div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3 border">
                    <div class="text-muted fw-semibold">Estado de pago</div>
                    <span class="badge <?= $psBadge ?> fs-6 rounded-pill"><?= htmlspecialchars($psLabel) ?></span>
                </div>

                <?php if ($paymentHint !== ''): ?>
                    <div class="alert alert-warning py-2 small mb-3 border-0 bg-warning-subtle text-warning-emphasis"><i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($paymentHint) ?></div>
                <?php endif; ?>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Monto Pagado</span>
                    <span class="fw-bold text-success fs-5"><?= money($paid) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4 pb-3 border-bottom">
                    <span class="text-muted">Saldo Pendiente</span>
                    <span class="fw-bold text-danger fs-5"><?= money($remain) ?></span>
                </div>

                <form method="post" action="<?= $bp ?>/admin/reservations/<?= $resId ?>/payment" class="js-lock-on-submit mb-4">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string)$csrf) ?>">
                    <input type="hidden" name="action" value="add_payment">

                    <label class="form-label fw-bold text-dark small text-uppercase">Registrar Abono</label>
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-white border-end-0 text-muted">$</span>
                        <input type="number" min="1" step="1" name="amount" class="form-control border-start-0 py-2" placeholder="Ej: 20000" <?= (!$canPay || $isRefunded) ? 'disabled' : '' ?> required>
                        <button class="btn btn-primary fw-semibold px-4" type="submit" <?= (!$canPay || $isRefunded) ? 'disabled' : '' ?>>Abonar</button>
                    </div>
                </form>

                <div class="d-flex gap-2 mb-4 pb-4 border-bottom">
                    <form method="post" action="<?= $bp ?>/admin/reservations/<?= $resId ?>/payment" class="flex-grow-1 js-lock-on-submit">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string)$csrf) ?>">
                        <input type="hidden" name="action" value="mark_paid">
                        <button class="btn btn-success w-100 fw-semibold shadow-sm" type="submit" <?= (!$canPay || $isPaid || $isRefunded) ? 'disabled' : '' ?> onclick="return confirm('¿Marcar como PAGADO TOTALMENTE?')">
                            <i class="bi bi-check2-all"></i> Marcar Pagado
                        </button>
                    </form>

                    <form method="post" action="<?= $bp ?>/admin/reservations/<?= $resId ?>/payment" class="js-lock-on-submit">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string)$csrf) ?>">
                        <input type="hidden" name="action" value="reset">
                        <button class="btn btn-outline-danger fw-semibold px-3" type="submit" <?= (!$canPay) ? 'disabled' : '' ?> onclick="return confirm('¿Resetear pagos a 0 (Pendiente)?')" title="Resetear a $0">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </form>
                </div>

                <form method="post" action="<?= $bp ?>/admin/reservations/<?= $resId ?>/payment" class="js-lock-on-submit">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars((string)$csrf) ?>">
                    <input type="hidden" name="action" value="set_status">

                    <label class="form-label fw-bold text-dark small text-uppercase">Forzar Estado Especial</label>
                    <div class="input-group">
                        <select name="payment_status_id" class="form-select bg-light" <?= (!$canPay) ? 'disabled' : '' ?>>
                            <option value="1" <?= $psId === 1 ? 'selected' : '' ?>>Pendiente</option>
                            <option value="2" <?= $psId === 2 ? 'selected' : '' ?>>Abonado</option>
                            <option value="3" <?= $psId === 3 ? 'selected' : '' ?>>Pagado</option>
                            <option value="4" <?= $psId === 4 ? 'selected' : '' ?>>Devuelto</option>
                        </select>
                        <button class="btn btn-secondary fw-semibold" type="submit" <?= (!$canPay) ? 'disabled' : '' ?> onclick="return confirm('¿Actualizar estado forzado?')">
                            Aplicar
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<script>
    // Bloqueador de doble envío seguro
    document.querySelectorAll('form.js-lock-on-submit').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (form.dataset.submitted === 'true') {
                e.preventDefault();
                return;
            }
            form.dataset.submitted = 'true';

            const btn = form.querySelector('button[type="submit"]:focus') || form.querySelector('button[type="submit"]');
            if (btn) {
                btn.style.pointerEvents = 'none';
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
            }
        });
    });
</script>