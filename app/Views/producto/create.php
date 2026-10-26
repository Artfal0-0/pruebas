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

<form action="<?= base_url('producto/store') ?>" method="post">
    <?= csrf_field() ?>  

    <p>
        <label for="nombre">Nombre:</label>
        <input type="text" 
               name="nombre" 
               placeholder="Nombre del producto" 
               required
               maxlength="100">
    </p>

    <p>
        <label for="precio">Precio:</label>
        <input type="number" 
               step="0.01" 
               min="0" 
               name="precio" 
               placeholder="Solo números" 
               required
               oninvalid="this.setCustomValidity('Solo se permiten números. No se permiten letras.')"
    </p>

    <p>
        <label for="stock">Stock:</label>
        <input type="number" 
               min="0" 
               name="stock" 
               placeholder="Solo números" 
               required
               oninvalid="this.setCustomValidity('Solo se permiten números. No se permiten letras.')"
    </p>
<br><br>
    <button type="submit">Guardar</button>
</form>

<br>
<a href="<?= base_url('producto') ?>">Volver</a>

</main>
</body>
</html>