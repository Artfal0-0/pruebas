<?= $this->include('layouts/header') ?>

<h1>Productos</h1>

<a href="<?= base_url('producto/create') ?>">Crear producto</a>

<br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Acción</th>
    </tr>

    <?php foreach ($productos as $producto): ?>
        <tr>
            <td><?= $producto['nombre'] ?></td>
            <td><?= $producto['precio'] ?></td>
            <td><?= $producto['stock'] ?></td>
            <td>
                <form onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?');" action="<?= base_url('producto/delete/' . $producto['id_producto']) ?>" method="post">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</main>
</body>
</html>