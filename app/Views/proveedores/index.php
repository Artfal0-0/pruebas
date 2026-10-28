<?= $this->include('layouts/header') ?>
<style>
    .panel { background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
    .panel-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .panel-top h1 { margin: 0; }
    .btn { display: inline-block; padding: 9px 14px; border: 0; border-radius: 7px; color: #fff; background: #172554; text-decoration: none; cursor: pointer; font-size: 14px; }
    .btn-danger { background: #b91c1c; }
    .btn-gris { background: #6b7280; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    th { background: #f9fafb; }
    .alert { padding: 10px 14px; margin-bottom: 16px; border-radius: 7px; }
    .alert-error { color: #991b1b; background: #fee2e2; }
    .pie { margin-top: 20px; }
    .vacio { padding: 20px; text-align: center; color: #6b7280; }
    .overlay { position: fixed; inset: 0; z-index: 1000; display: flex; align-items: center; justify-content: center; background: rgba(15,23,42,.55); }
    .overlay[hidden] { display: none; }
    .caja { min-width: 270px; padding: 28px 36px; text-align: center; background: #fff; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.25); animation: aparece .25s ease-out; }
    .caja h2 { margin: 0 0 6px; }
    .caja p { margin: 0 0 16px; color: #374151; }
    .check { width: 56px; height: 56px; margin: 0 auto 12px; line-height: 56px; font-size: 32px; color: #fff; background: #16a34a; border-radius: 50%; }
    @keyframes aparece { from { opacity: 0; transform: scale(.9); } to { opacity: 1; transform: scale(1); } }
</style>

<div class="panel">
    <div class="panel-top">
        <h1>Proveedores</h1>
        <a class="btn" href="<?= base_url('proveedores/create') ?>">+ Nuevo proveedor</a>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-error"><?= esc(implode(' ', session()->getFlashdata('errors'))) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>RUC</th>
                <th>Ubicación</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (! empty($proveedores)): ?>
            <?php foreach ($proveedores as $i => $p): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($p['ruc']) ?></td>
                    <td><?= esc($p['ubicacion']) ?></td>
                    <td><?= esc($p['telefono']) ?></td>
                    <td>
                        <form action="<?= base_url('proveedores/delete/' . $p['id_proveedor']) ?>" method="post"
                              onsubmit="return confirm('¿Eliminar el proveedor con RUC <?= esc($p['ruc'], 'js') ?>?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="vacio">No hay proveedores registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="pie">
        <a class="btn btn-gris" href="<?= base_url('/') ?>">← Volver al menú principal</a>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="overlay" id="aviso">
    <div class="caja">
        <div class="check">✓</div>
        <h2>¡Éxito!</h2>
        <p><?= esc(session()->getFlashdata('success')) ?></p>
        <button type="button" class="btn" onclick="cerrarAviso()">Aceptar</button>
    </div>
</div>
<script>
    function cerrarAviso() { document.getElementById('aviso').hidden = true; }
    setTimeout(cerrarAviso, 2500);
</script>
<?php endif; ?>
</main>
</body>
</html>