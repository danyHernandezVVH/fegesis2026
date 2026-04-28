<?php

/** @var array $games */
/** @var string $csrf */
$bp = $basePath ?? '';
?>

<style>
    :root {
        --fegesis-primary: #0d6efd;
        --fegesis-soft-bg: #f8fbff;
    }

    .card-form {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .section-title {
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

    .section-title i {
        color: var(--fegesis-primary);
        font-size: 1.1rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #334155;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 0.6rem 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--fegesis-primary);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    .availability-badge {
        display: none;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 8px;
        margin-top: 8px;
    }

    .bg-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .bg-soft-success {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    .input-group>.form-control {
        border-radius: 0 10px 10px 0;
    }
</style>

<div class="row justify-content-center">
    <div class="col-12 col-xl-10">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold">Crear Nueva Reserva</h1>
                <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i> Ingresa los detalles para validar disponibilidad en tiempo real.</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-bold" href="<?= htmlspecialchars($bp . '/admin/reservations') ?>">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
        </div>

        <div class="card card-form overflow-hidden">
            <div class="card-body p-4 p-md-5">
                <form method="post" action="<?= htmlspecialchars($bp . '/admin/reservations/create') ?>" id="adminResForm">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">

                    <div class="section-title"><i class="bi bi-stars"></i> Detalles del Juego y Tiempo</div>
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label class="form-label">Juego para reservar *</label>
                            <select class="form-select shadow-sm" name="game_id" id="game_id" required>
                                <option value="">Seleccionar juego...</option>
                                <?php foreach ($games as $g): ?>
                                    <option value="<?= (int)$g['id'] ?>"><?= htmlspecialchars((string)$g['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fecha del Evento *</label>
                            <input class="form-control shadow-sm" type="date" name="event_date" id="event_date" min="<?= date('Y-m-d') ?>" required>
                            <div id="availabilityBadge" class="availability-badge"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado de Gestión *</label>
                            <select class="form-select shadow-sm fw-bold text-primary" name="status_id" required>
                                <option value="1">Pendiente</option>
                                <option value="2">Confirmada ✅</option>
                                <option value="4">Rechazada ❌</option>
                                <option value="3">Cancelada ⚠️</option>
                            </select>
                        </div>
                    </div>

                    <div class="section-title"><i class="bi bi-geo-alt"></i> Ubicación y Despacho</div>
                    <div class="row g-4 mb-5">
                        <div class="col-md-8">
                            <label class="form-label">Dirección Exacta *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-map"></i></span>
                                <input class="form-control" name="direccion" maxlength="190" required placeholder="Ej: Av. Las Condes 1234, depto 502">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Comuna *</label>
                            <input class="form-control" name="comuna" maxlength="120" required placeholder="Ej: Vitacura">
                        </div>
                    </div>

                    <div class="section-title"><i class="bi bi-person-badge"></i> Datos del Cliente</div>
                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <label class="form-label">Nombre completo</label>
                            <input class="form-control" name="name" maxlength="120" placeholder="Ej: Pedro Rodríguez">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono de contacto</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                                <input class="form-control" name="phone" maxlength="30" placeholder="+56 9 1234 5678">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Correo electrónico</label>
                            <input class="form-control" name="email" maxlength="190" placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    <div class="section-title"><i class="bi bi-journal-text"></i> Notas e Información Interna</div>
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label text-muted small">Notas del Cliente (Públicas)</label>
                            <textarea class="form-control" name="mensaje" rows="4" maxlength="500" placeholder="Instrucciones del cliente..."></textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label text-muted small">Nota Administrativa (Solo Interna)</label>
                            <textarea class="form-control" name="internal_reason" rows="4" maxlength="500" placeholder="Ej: Reservado por teléfono. Descuento aplicado por fidelidad."></textarea>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-light p-4 rounded-4 mt-4">
                        <div class="text-muted small mb-3 mb-md-0">
                            <i class="bi bi-shield-check me-1"></i> Al crear una reserva <b>Confirmada</b>, el stock del juego quedará bloqueado automáticamente.
                        </div>
                        <button class="btn btn-primary btn-lg px-5 fw-bold shadow-sm rounded-3" type="submit" id="btnSave">
                            <i class="bi bi-plus-circle me-2"></i> Crear Reserva Ahora
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const bp = <?= json_encode($bp) ?>;
        const gameSel = document.getElementById('game_id');
        const dateInp = document.getElementById('event_date');
        const badge = document.getElementById('availabilityBadge');
        const btnSave = document.getElementById('btnSave');

        let unavailable = new Set();

        function showBadge(msg, type = 'success') {
            badge.textContent = msg;
            badge.className = `availability-badge bg-soft-${type}`;
            badge.style.display = msg ? 'inline-block' : 'none';
        }

        function disableIfUnavailable() {
            const d = dateInp.value;
            if (!d) {
                btnSave.disabled = false;
                showBadge('');
                return;
            }

            if (unavailable.has(d)) {
                btnSave.disabled = true;
                showBadge('⚠️ No disponible (Fecha ocupada)', 'danger');
            } else {
                btnSave.disabled = false;
                showBadge('✅ Disponible para reservar', 'success');
            }
        }

        async function loadAvailability() {
            const gid = parseInt(gameSel.value || '0', 10);
            if (!gid) {
                unavailable = new Set();
                showBadge('');
                return;
            }

            try {
                const res = await fetch(`${bp}/api/availability/${gid}`);
                const data = await res.json();
                unavailable = new Set(data.unavailable || []);
                disableIfUnavailable();
            } catch (e) {
                showBadge('Falla al validar stock', 'danger');
            }
        }

        gameSel.addEventListener('change', loadAvailability);
        dateInp.addEventListener('change', disableIfUnavailable);
    })();
</script>