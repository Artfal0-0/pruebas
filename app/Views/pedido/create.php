
<?= $this->include('layouts/header') ?>

<!-- Bootstrap 5, solo para la vista de Pedidos -->
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<style>
    /* Mantener el diseño del encabezado */
    .site-header {
        background: #172554;
    }

    .site-header .nav-group a {
        color: #e0e7ff;
        text-decoration: none;
    }

    .site-header .nav-group a:hover {
        background: #fff;
        color: #172554;
    }

    /* Diseño del formulario */
    .pedido-card {
        max-width: 600px;
        background: white;
        border-radius: 14px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }

    .pedido-card h1 {
        color: #142640;
    }

    .pedido-card .form-control:focus,
    .pedido-card .form-select:focus {
        border-color: #172554;
        box-shadow: 0 0 0 .2rem rgba(23,37,84,.12);
    }

    .pedido-card .btn-primary {
        background-color: #172554;
        border-color: #172554;
    }

    .pedido-card .btn-primary:hover {
        background-color: #1e3a8a;
        border-color: #1e3a8a;
    }
</style>

<div class="pedido-card p-4">

    <h1 class="fw-bold mb-4">Crear pedido</h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p class="mb-1"><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('pedido/store') ?>"
          method="post">

        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="descripcion"
                   class="form-label fw-semibold">
                Descripción
            </label>

            <textarea
                class="form-control"
                id="descripcion"
                name="descripcion"
                rows="3"
                placeholder="Ingrese la descripción del pedido"
                required
            ><?= esc(old('descripcion')) ?></textarea>
        </div>

        <div class="mb-4">
            <label for="estado"
                   class="form-label fw-semibold">
                Estado
            </label>

            <select class="form-select"
                    id="estado"
                    name="estado"
                    required>

                <?php foreach (['Pendiente', 'En proceso', 'Completado', 'Cancelado'] as $estado): ?>
                    <option
                        value="<?= esc($estado) ?>"
                        <?= old('estado', 'Pendiente') === $estado ? 'selected' : '' ?>>
                        <?= esc($estado) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit"
                    class="btn btn-primary px-4">
                Guardar
            </button>

            <a href="<?= site_url('pedido') ?>"
               class="btn btn-secondary px-4">
                Cancelar
            </a>
        </div>

    </form>
</div>

</main>
</body>
</html>
