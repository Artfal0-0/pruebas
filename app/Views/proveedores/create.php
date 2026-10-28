<?= $this->include('layouts/header') ?>

<div class="panel">
    <h1>Crear proveedor</h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('proveedores/store') ?>" method="post">
        <?= csrf_field() ?>

        <label for="ruc">RUC</label>
        <input type="text" placeholder="1790012345001" id="ruc" name="ruc" value="<?= esc(old('ruc')) ?>" maxlength="13" inputmode="numeric" pattern="[0-9]{13}" title="El RUC debe tener 13 dígitos" required>

        <label for="ubicacion">Ubicación</label>
        <input type="text" placeholder="Quito, Av. Amazonas" id="ubicacion" name="ubicacion" value="<?= esc(old('ubicacion')) ?>" maxlength="100" required>

        <label for="telefono">Teléfono</label>
        <input type="text" placeholder="0991234567" id="telefono" name="telefono" value="<?= esc(old('telefono')) ?>" maxlength="10" inputmode="numeric" pattern="[0-9]{7,10}" title="Entre 7 y 10 dígitos" required>

        <div class="acciones">
            <button type="submit" class="btn">Guardar</button>
            <a class="btn btn-gris" href="<?= base_url('proveedores') ?>">Cancelar</a>
        </div>
    </form>
</div>
<div class="overlay" id="cargando" hidden>
    <div class="caja">
        <div class="spinner"></div>
        <h2>Guardando...</h2>
        <p>Espera un momento</p>
    </div>
</div>

<script>
    // Mensajes en español para los campos obligatorios
    var mensajes = {
        ruc:       'Por favor, escribe el RUC.',
        ubicacion: 'Por favor, escribe la ubicación.',
        telefono:  'Por favor, escribe el teléfono.'
    };
    var mensajesFormato = {
        ruc:      'El RUC debe tener 13 dígitos.',
        telefono: 'El teléfono debe tener entre 7 y 10 dígitos.'
    };
    document.querySelectorAll('input[required]').forEach(function (campo) {
        campo.addEventListener('invalid', function () {
            if (campo.validity.patternMismatch) {
                campo.setCustomValidity(mensajesFormato[campo.name] || 'Formato no válido.');
            } else {
                campo.setCustomValidity(mensajes[campo.name] || 'Por favor, llena este campo.');
            }
        });
        campo.addEventListener('input', function () { campo.setCustomValidity(''); });
    });

    // RUC y teléfono: solo números
    ['ruc', 'telefono'].forEach(function (id) {
        document.getElementById(id).addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
        });
    });

    document.querySelector('form').addEventListener('submit', function (e) {
        if (this.dataset.enviando) return;
        e.preventDefault();
        this.dataset.enviando = '1';
        document.getElementById('cargando').hidden = false;
        setTimeout(() => this.submit(), 600);
    });
</script>
</main>
</body>
</html>

<style>
    .panel { max-width: 480px; background: #fff; border-radius: 10px; padding: 24px; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
    label { display: block; margin: 14px 0 6px; font-weight: bold; }
    input[type=text] { width: 100%; padding: 9px; border: 1px solid #d1d5db; border-radius: 7px; font-size: 14px; }
    .btn { display: inline-block; padding: 9px 14px; border: 0; border-radius: 7px; color: #fff; background: #172554; text-decoration: none; cursor: pointer; font-size: 14px; }
    .btn-gris { background: #6b7280; }
    .acciones { display: flex; gap: 10px; margin-top: 20px; }
    .alert { padding: 10px 14px; margin-bottom: 16px; border-radius: 7px; color: #991b1b; background: #fee2e2; }
    .alert ul { margin: 0; padding-left: 18px; }
    .overlay { position: fixed; inset: 0; z-index: 1000; display: flex; align-items: center; justify-content: center; background: rgba(15,23,42,.55); }
    .overlay[hidden] { display: none; }
    .caja { min-width: 270px; padding: 28px 36px; text-align: center; background: #fff; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.25); animation: aparece .25s ease-out; }
    .caja h2 { margin: 0 0 6px; }
    .caja p { margin: 0 0 16px; color: #374151; }
    .spinner { width: 44px; height: 44px; margin: 0 auto 14px; border: 5px solid #e5e7eb; border-top-color: #172554; border-radius: 50%; animation: giro .8s linear infinite; }
    .check { width: 56px; height: 56px; margin: 0 auto 12px; line-height: 56px; font-size: 32px; color: #fff; background: #16a34a; border-radius: 50%; }
    @keyframes giro { to { transform: rotate(360deg); } }
    @keyframes aparece { from { opacity: 0; transform: scale(.9); } to { opacity: 1; transform: scale(1); } }
</style>