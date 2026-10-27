<?= $this->include('layouts/header') ?>

<style>
    .tabla-facturas {
        border-collapse: separate;
        border-spacing: 0;
    }

    .tabla-facturas th,
    .tabla-facturas td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    .tabla-facturas thead th {
        white-space: nowrap;
    }

    .tabla-facturas tbody tr:hover {
        background-color: #f8f9fa;
    }

    .acciones-factura {
        display: flex;
        justify-content: center;
        gap: 8px;
        align-items: center;
    }

    .acciones-factura form {
        margin: 0;
    }

    .acciones-factura .btn {
        white-space: nowrap;
    }
</style>

<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Facturas</h1>
            <p class="text-muted mb-0">
                Consulta y administra las facturas registradas.
            </p>
        </div>

        <a href="<?= site_url('factura/create') ?>"
           class="btn btn-primary">
            + Crear factura
        </a>
    </div>

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('mensaje')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover tabla-facturas">

                    <thead class="table-dark">
                        <tr>
                            <th>N.º</th>
                            <th>Cliente</th>
                            <th>Cédula</th>
                            <th>Fecha de emisión</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($facturas)): ?>

                            <?php foreach ($facturas as $factura): ?>
                                <tr>
                                    <td><?= esc($factura['id_factura']) ?></td>

                                    <td><?= esc($factura['nombre_cliente']) ?></td>

                                    <td><?= esc($factura['cedula_cliente']) ?></td>

                                    <td><?= esc($factura['fecha_emision']) ?></td>

                                    <td class="text-end">
                                        $<?= number_format((float) $factura['total'], 2, '.', ',') ?>
                                    </td>

                                    <td>
                                        <div class="acciones-factura">

                                            <a
                                                href="<?= site_url('factura/detalle/' . $factura['id_factura']) ?>"
                                                class="btn btn-sm btn-info text-white">
                                                Ver detalle
                                            </a>

                                            <form
                                                action="<?= site_url('factura/delete/' . $factura['id_factura']) ?>"
                                                method="post"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar esta factura?');">

                                                <?= csrf_field() ?>

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Eliminar
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Todavía no hay facturas registradas.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

</main>
</body>
</html>