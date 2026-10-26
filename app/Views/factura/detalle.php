<?= $this->include('layouts/header') ?>
<div class="page-header">
    <div>
        <h1>Factura #<?= esc($factura['id_factura']) ?></h1>
        <p class="page-subtitle">Detalle completo de la factura.</p>
    </div>
    <a class="btn btn-secondary" href="<?= base_url('factura') ?>">Volver al listado</a>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success"><?= esc(session('success')) ?></div>
<?php endif; ?>

<?php if (session('error')): ?>
    <div class="alert alert-error"><?= esc(session('error')) ?></div>
<?php endif; ?>

<section class="card">
    <h2>Datos generales</h2>
    <div class="info-grid">
        <div class="info-item">
            <span>Cliente</span>
            <strong><?= esc($factura['cliente_nombre'] ?? 'Sin cliente') ?></strong>
        </div>
        <div class="info-item">
            <span>Cédula</span>
            <strong><?= esc($factura['cliente_cedula'] ?? '—') ?></strong>
        </div>
        <div class="info-item">
            <span>Teléfono</span>
            <strong><?= esc($factura['cliente_telefono'] ?: '—') ?></strong>
        </div>
        <div class="info-item">
            <span>Fecha de emisión</span>
            <strong><?= esc($factura['fecha_emision']) ?></strong>
        </div>
        <div class="info-item">
            <span>Artículos</span>
            <strong><?= esc($factura['cantidad']) ?></strong>
        </div>
    </div>
</section>

<section class="card">
    <h2>Productos de la factura</h2>

    <?php if (empty($detalle)): ?>
        <p class="empty">Esta factura no tiene productos registrados.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Precio unitario</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle as $linea): ?>
                        <tr>
                            <td><?= esc($linea['producto_nombre'] ?? 'Producto eliminado') ?></td>
                            <td class="text-right"><?= esc($linea['cantidad']) ?></td>
                            <td class="text-right">$<?= number_format((float) $linea['precio_unitario'], 2) ?></td>
                            <td class="text-right">$<?= number_format((float) $linea['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="total-line">
        <span>Total:</span>
        <strong>$<?= number_format((float) $factura['total'], 2) ?></strong>
    </div>
</section>

<div class="form-actions">
    <a class="btn btn-primary" href="<?= base_url('factura/create') ?>">Nueva factura</a>
    <form action="<?= base_url('factura/delete/' . $factura['id_factura']) ?>" method="post" onsubmit="return confirm('¿Eliminar esta factura?');">
        <button type="submit" class="btn btn-danger">Eliminar factura</button>
    </form>
</div>
</main>
</body>
</html>
