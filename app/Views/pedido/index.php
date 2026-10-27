
<?= $this->include('layouts/header') ?>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<h1>Pedidos</h1>

<a href="<?= site_url('pedido/create') ?>" class="btn btn-primary mb-3">
    Nuevo Pedido
</a>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Fecha de creación</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= esc($pedido['id_pedido']) ?></td>
                <td><?= esc($pedido['descripcion']) ?></td>
                <td><?= esc($pedido['estado']) ?></td>
                <td><?= esc($pedido['fecha_creacion']) ?></td>
                <td>
                    <form
                        action="<?= site_url('pedido/delete/' . $pedido['id_pedido']) ?>"
                        method="post"
                        onsubmit="return confirm('¿Seguro que deseas eliminar este pedido?');"
                    >
                        <?= csrf_field() ?>

                        <button type="submit" class="btn btn-danger btn-sm">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($pedidos)): ?>
            <tr>
                <td colspan="5" class="text-center">
                    No existen pedidos registrados.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</main>
</body>
</html>
