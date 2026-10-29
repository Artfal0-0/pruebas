<?= $this->include('layouts/header') ?>
<style>
    .panel { max-width: 480px; background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
    label { display: block; margin: 14px 0 6px; font-weight: bold; }
    input[type=text] { width: 100%; padding: 9px; border: 1px solid #d1d5db; border-radius: 7px; font-size: 14px; }
    .btn { display: inline-block; padding: 9px 14px; border: 0; border-radius: 7px; color: #fff; background: #172554; text-decoration: none; cursor: pointer; font-size: 14px; }
    .btn-gris { background: #6b7280; }
    .acciones { display: flex; gap: 10px; margin-top: 20px; }
    .alert { padding: 10px 14px; margin-bottom: 16px; border-radius: 7px; color: #991b1b; background: #fee2e2; }
    .alert ul { margin: 0; padding-left: 18px; }
</style>

<div class="panel">
    <h1>Editar cliente</h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('clientes/update/' . $cliente['id_cliente']) ?>" method="post">
        <?= csrf_field() ?>

        <label for="nombre">Nombre</label>
        <input type="text" placeholder="Nombre del cliente" id="nombre" name="nombre" value="<?= esc(old('nombre', $cliente['nombre'])) ?>" maxlength="100" pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+" title="Solo letras" required>

        <label for="cedula">Cédula</label>
        <input type="text" placeholder="10 dígitos" id="cedula" name="cedula" value="<?= esc(old('cedula', $cliente['cedula'])) ?>" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" title="Solo números y 10 dígitos" required>

        <label for="telefono">Teléfono</label>
        <input type="text" placeholder="10 dígitos" id="telefono" name="telefono" value="<?= esc(old('telefono', $cliente['telefono'])) ?>" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" title="Solo números y 10 dígitos" required>

        <div class="acciones">
            <button type="submit" class="btn">Guardar cambios</button>
            <a class="btn btn-gris" href="<?= base_url('clientes') ?>">Cancelar</a>
        </div>
    </form>
</div>

<script>
    // Mensajes en español cuando un campo está vacío
    var mensajesVacio = {
        nombre:   'Por favor, escribe el nombre.',
        cedula:   'Por favor, escribe la cédula.',
        telefono: 'Por favor, escribe el teléfono.'
    };

    // Mensajes en español cuando el dato no cumple el formato
    var mensajesFormato = {
        nombre:   'El nombre solo puede contener letras.',
        cedula:   'La cédula debe tener 10 dígitos numéricos.',
        telefono: 'El teléfono debe tener 10 dígitos numéricos.'
    };

    document.querySelectorAll('input[required]').forEach(function (campo) {
        campo.addEventListener('invalid', function () {
            if (campo.validity.valueMissing) {
                campo.setCustomValidity(mensajesVacio[campo.name] || 'Por favor, llena este campo.');
            } else if (campo.validity.patternMismatch) {
                campo.setCustomValidity(mensajesFormato[campo.name] || 'El valor no es válido.');
            } else {
                campo.setCustomValidity('');
            }
        });

        campo.addEventListener('input', function () {
            campo.setCustomValidity('');
        });
    });
</script>

</main>
</body>
</html>
