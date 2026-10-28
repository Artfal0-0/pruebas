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
    .alert-success { color: #166534; background: #dcfce7; }
    .vacio { padding: 20px; text-align: center; color: #6b7280; }
    .pie { margin-top: 20px; }
</style>

<div class="panel">
    <div class="panel-top">
        <h1>Clientes</h1>
        <a class="btn" href="<?= base_url('clientes/create') ?>">+ Nuevo cliente</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (! empty($clientes)): ?>
            <?php foreach ($clientes as $i => $cliente): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($cliente['nombre']) ?></td>
                    <td><?= esc($cliente['cedula']) ?></td>
                    <td><?= esc($cliente['telefono']) ?></td>
                    <td>
                        <form action="<?= base_url('clientes/delete/' . $cliente['id_cliente']) ?>" method="post"
                              onsubmit="return confirm('¿Eliminar el cliente <?= esc($cliente['nombre'], 'js') ?>?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="vacio">No hay clientes registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="pie">
        <a class="btn btn-gris" href="<?= base_url('/') ?>">← Volver al menú principal</a>
    </div>
</div>

</main>
</body>
</html>
