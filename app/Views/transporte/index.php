<?= $this->include('layouts/header') ?>
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<h1>Listado de Transportes</h1>

<a href="<?= site_url('transporte/create') ?>" class="btn btn-primary mb-3">
    + Nuevo Transporte
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
            <th>Empresa</th>
            <th>Tipo Vehículo</th>
            <th>Teléfono Contacto</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($transportes as $item): ?>
            <tr>
                <td><?= esc($item['id_transporte']) ?></td>
                <td><?= esc($item['nombre_empresa']) ?></td>
                <td><?= esc($item['tipo_vehiculo']) ?></td>
                <td><?= esc($item['telefono_contacto']) ?></td>
                <td>
                    <form
                        action="<?= site_url('transporte/delete/' . $item['id_transporte']) ?>"
                        method="post"
                        onsubmit="return confirm('¿Seguro que deseas eliminar este transporte?');"
                    >
                        <?= csrf_field() ?>

                        <button type="submit" class="btn btn-danger btn-sm">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($transportes)): ?>
            <tr>
                <td colspan="5" class="text-center">
                    No existen transportes registrados.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</main>
</body>
</html>