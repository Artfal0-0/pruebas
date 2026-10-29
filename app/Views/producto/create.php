<?= $this->include('layouts/header') ?>

<h1>Crear producto</h1>

<?php if (session()->getFlashdata('errors')): ?>
    <div style="color: red; margin-bottom: 15px;">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('producto/store') ?>" method="post" id="formProducto">

    <p>
        <label for="nombre">Nombre:</label>
        <input type="text" 
               name="nombre" 
               id="nombre"
               placeholder="Nombre del producto" 
               required
               maxlength="100"
               value="<?= esc(old('nombre')) ?>">
    </p>

    <p>
        <label for="precio">Precio:</label>
        <input type="text" 
               name="precio" 
               id="precio"
               placeholder="Ej: 12.50 o 12,50" 
               required
               value="<?= esc(old('precio')) ?>">
    </p>

    <p>
        <label for="stock">Stock:</label>
        <input type="number"
               min="0"
               step ="1"
               name="stock" 
               id="stock"
               placeholder="Solo números enteros" 
               required
               value="<?= esc(old('stock')) ?>">
    </p>
    <br>
    <button type="submit" id="btnGuardar">Guardar</button>
</form>

<br>
<a href="<?= base_url('producto') ?>">Volver</a>
<script>
    document.getElementById('formProducto').addEventListener('submit', function() {
        const boton = document.getElementById('btnGuardar');
        boton.disabled = true;
        boton.innerText = 'Guardando...';
    });
</script>
</main>
</body>
</html>