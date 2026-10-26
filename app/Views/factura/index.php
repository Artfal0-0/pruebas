<?= $this->include('layouts/header') ?>
<div class="page-header">
    <div>
        <h1>Facturas</h1>
        <p class="page-subtitle">Consulta las facturas registradas y el detalle de cada una.</p>
    </div>
    <a class="btn btn-primary" href="<?= base_url('factura/create') ?>">Nueva factura</a>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success"><?= esc(session('success')) ?></div>
<?php endif; ?>

<?php if (session('error')): ?>
    <div class="alert alert-error"><?= esc(session('error')) ?></div>
<?php endif; ?>

<section class="card">
    <h2>Listado de facturas</h2>

    <?php if (empty($facturas)): ?>
        <p class="empty">No hay facturas registradas todavía.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Fecha de emisión</th>
                        <th class="text-right">Artículos</th>
                        <th class="text-right">Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facturas as $factura): ?>
                        <tr>
                            <td><?= esc($factura['id_factura']) ?></td>
                            <td>
                                <strong><?= esc($factura['cliente_nombre'] ?? 'Sin cliente') ?></strong>
                                <?php if (! empty($factura['cliente_cedula'])): ?>
                                    <span class="muted"><?= esc($factura['cliente_cedula']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($factura['fecha_emision']) ?></td>
                            <td class="text-right"><?= esc($factura['cantidad']) ?></td>
                            <td class="text-right">$<?= number_format((float) $factura['total'], 2) ?></td>
                            <td>
                                <div class="actions">
                                    <a class="btn btn-secondary btn-sm" href="<?= base_url('factura/detalle/' . $factura['id_factura']) ?>">Ver detalle</a>
                                    <form action="<?= base_url('factura/delete/' . $factura['id_factura']) ?>" method="post" onsubmit="return confirm('¿Eliminar esta factura?');">
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
</main>
</body>
</html>
