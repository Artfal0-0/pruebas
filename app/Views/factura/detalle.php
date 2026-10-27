<?= $this->include('layouts/header') ?>

<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Detalle de factura</h1>
            <p class="text-muted mb-0">
                Información del cliente y productos facturados.
            </p>
        </div>

        <a href="<?= site_url('factura') ?>"
           class="btn btn-secondary">
            Volver a facturas
        </a>
    </div>

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('mensaje')) ?>
        </div>
    <?php endif; ?>

    <!-- Información principal -->
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            <h5 class="mb-0">
                Factura N.º <?= esc($factura['id_factura']) ?>
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <strong>Cliente:</strong>
                    <p><?= esc($factura['nombre_cliente']) ?></p>
                </div>

                <div class="col-md-6">
                    <strong>Cédula:</strong>
                    <p><?= esc($factura['cedula_cliente']) ?></p>
                </div>

                <div class="col-md-6">
                    <strong>Teléfono:</strong>
                    <p>
                        <?= esc(
                            $factura['telefono_cliente'] ?: 'No registrado'
                        ) ?>
                    </p>
                </div>

                <div class="col-md-6">
                    <strong>Fecha de emisión:</strong>
                    <p><?= esc($factura['fecha_emision']) ?></p>
                </div>

            </div>

        </div>
    </div>

    <!-- Productos facturados -->
    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">Productos facturados</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th class="text-end">Precio unitario</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($detalles)): ?>

                            <?php foreach ($detalles as $detalle): ?>
                                <tr>

                                    <td>
                                        <?= esc($detalle['nombre_producto']) ?>
                                    </td>

                                    <td class="text-end">
                                        $<?= number_format(
                                            (float) $detalle['precio_unitario'],
                                            2,
                                            '.',
                                            ','
                                        ) ?>
                                    </td>

                                    <td class="text-center">
                                        <?= esc($detalle['cantidad']) ?>
                                    </td>

                                    <td class="text-end">
                                        $<?= number_format(
                                            (float) $detalle['subtotal'],
                                            2,
                                            '.',
                                            ','
                                        ) ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="4" class="text-center">
                                    Esta factura no tiene detalles registrados.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">
                                TOTAL DE LA FACTURA:
                            </th>

                            <th class="text-end">
                                $<?= number_format(
                                    (float) $factura['total'],
                                    2,
                                    '.',
                                    ','
                                ) ?>
                            </th>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
    </div>

</div>

</main>
</body>
</html>