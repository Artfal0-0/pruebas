<?= $this->include('layouts/header') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Agregar Nuevo Transporte</h4>
        </div>
        <div class="card-body">

            <!-- Alerta para mostrar errores de validación del servidor -->
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('transporte/store') ?>" method="post">
                <!-- Token de seguridad contra ataques CSRF e inyecciones externas -->
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="nombre_empresa" class="form-label">Nombre Empresa: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="<?= old('nombre_empresa') ?>" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios, sin números ni caracteres especiales">
                    <div class="form-text">Solo letras y espacios.</div>
                </div>
                
                <div class="mb-3">
                    <label for="tipo_vehiculo" class="form-label">Tipo Vehículo: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="tipo_vehiculo" name="tipo_vehiculo" value="<?= old('tipo_vehiculo') ?>" required pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras y espacios, sin números ni caracteres especiales">
                    <div class="form-text">Solo letras y espacios.</div>
                </div>

                <div class="mb-3">
                    <label for="telefono_contacto" class="form-label">Teléfono de Contacto (Número): <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="telefono_contacto" name="telefono_contacto" value="<?= old('telefono_contacto') ?>" required pattern="[0-9]+" title="Solo se permiten números">
                    <div class="form-text">Solo números (sin guiones ni letras).</div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= base_url('transporte') ?>" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Guardar Transporte</button>
                </div>
            </form>
        </div>
    </div>
</div>

</main>
</body>
</html>