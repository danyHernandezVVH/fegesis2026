<?php
/** @var string $title */
/** @var string $csrf */
/** @var ?string $error */
?>

<div class="w-100" style="max-width: 400px;">
  
  <div class="text-center mb-4">
    <h3 class="fw-bold text-dark">Bienvenido</h3>
    <p class="text-muted small">Ingresa tus credenciales para administrar.</p>
  </div>

  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body p-4 p-md-5">

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2 p-2 small mb-3 border-0 bg-danger-subtle text-danger">
          <i class="bi bi-exclamation-circle-fill"></i>
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= htmlspecialchars(($basePath ?? '') . '/admin/login') ?>" autocomplete="off">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf) ?>">

        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="floatingInput" name="email" placeholder="nombre@ejemplo.com" required maxlength="190">
          <label for="floatingInput">Correo Electrónico</label>
        </div>

        <div class="form-floating mb-4">
          <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Contraseña" required minlength="6">
          <label for="floatingPassword">Contraseña</label>
        </div>

        <button class="btn btn-primary w-100 py-2 fw-semibold rounded-3 shadow-sm" type="submit">
          Ingresar al Panel
        </button>

      </form>
    </div>
  </div>

  <div class="text-center mt-4">
    <small class="text-muted">
      &copy; <?= date('Y') ?> Fegesis Admin &bull; <span class="text-secondary">Acceso Restringido</span>
    </small>
  </div>
</div>