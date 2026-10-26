<?php
$lineasIniciales = [];
$productosOld    = old('productos');
$cantidadesOld   = old('cantidades');

if (is_array($productosOld)) {
    foreach ($productosOld as $indice => $idProducto) {
        $idProducto = (int) $idProducto;
        $cantidad   = (int) ($cantidadesOld[$indice] ?? 0);

        if ($idProducto > 0) {
            $lineasIniciales[] = [
                'id_producto' => $idProducto,
                'cantidad'    => $cantidad > 0 ? $cantidad : 1,
            ];
        }
    }
}

if ($lineasIniciales === []) {
    $lineasIniciales[] = ['id_producto' => 0, 'cantidad' => 1];
}
?>
<?= $this->include('layouts/header') ?>
<div class="page-header">
    <div>
        <h1>Nueva factura</h1>
        <p class="page-subtitle">Selecciona el cliente y agrega los productos de la factura.</p>
    </div>
    <a class="btn btn-secondary" href="<?= base_url('factura') ?>">Volver al listado</a>
</div>

<?php if (session('error')): ?>
    <div class="alert alert-error"><?= esc(session('error')) ?></div>
<?php endif; ?>

<?php if (empty($clientes) || empty($productos)): ?>
    <div class="alert alert-error">
        Para registrar una factura necesitas al menos un cliente y un producto.
        <?php if (empty($clientes)): ?>
            <a href="<?= base_url('clientes/create') ?>">Registrar cliente</a>
        <?php endif; ?>
        <?php if (empty($productos)): ?>
            <a href="<?= base_url('producto/create') ?>">Registrar producto</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<form action="<?= base_url('factura/store') ?>" method="post">
    <section class="card">
        <h2>Datos generales</h2>
        <div class="form-field">
            <label for="id_cliente">Cliente</label>
            <select name="id_cliente" id="id_cliente" required>
                <option value="">Selecciona un cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= esc($cliente['id_cliente'], 'attr') ?>" <?= (string) old('id_cliente') === (string) $cliente['id_cliente'] ? 'selected' : '' ?>>
                        <?= esc($cliente['nombre']) ?> — <?= esc($cliente['cedula']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </section>

    <section class="card">
        <h2>Detalle de la factura</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th class="text-right">Precio unitario</th>
                        <th class="text-right">Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="cuerpo-lineas"></tbody>
            </table>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" id="agregar-linea">Agregar producto</button>
        </div>

        <div class="total-line">
            <span>Total:</span>
            <strong id="total-factura">$0.00</strong>
        </div>
    </section>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar factura</button>
        <a class="btn btn-secondary" href="<?= base_url('factura') ?>">Cancelar</a>
    </div>

    <template id="plantilla-linea">
        <tr>
            <td>
                <select name="productos[]" class="linea-producto" required>
                    <option value="">Selecciona un producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= esc($producto['id_producto'], 'attr') ?>" data-precio="<?= esc($producto['precio'], 'attr') ?>">
                            <?= esc($producto['nombre']) ?> — $<?= number_format((float) $producto['precio'], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="number" name="cantidades[]" class="qty-input linea-cantidad" value="1" min="1" step="1" required>
            </td>
            <td class="text-right linea-precio">$0.00</td>
            <td class="text-right linea-subtotal">$0.00</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm quitar-linea">Quitar</button>
            </td>
        </tr>
    </template>
</form>

<script>
    (function () {
        const plantilla = document.getElementById('plantilla-linea');
        const cuerpo = document.getElementById('cuerpo-lineas');
        const totalFactura = document.getElementById('total-factura');
        const formatear = (valor) => '$' + Number(valor || 0).toFixed(2);

        function recalcular() {
            let total = 0;

            cuerpo.querySelectorAll('tr').forEach((fila) => {
                const select = fila.querySelector('.linea-producto');
                const cantidad = fila.querySelector('.linea-cantidad');
                const opcion = select.options[select.selectedIndex];
                const precio = opcion && opcion.dataset.precio ? Number(opcion.dataset.precio) : 0;
                const cantidadValor = Math.max(0, parseInt(cantidad.value, 10) || 0);
                const subtotal = precio * cantidadValor;

                fila.querySelector('.linea-precio').textContent = formatear(precio);
                fila.querySelector('.linea-subtotal').textContent = formatear(subtotal);
                total += subtotal;
            });

            totalFactura.textContent = formatear(total);
        }

        function crearFila(idProducto, cantidad) {
            const fila = plantilla.content.firstElementChild.cloneNode(true);
            const select = fila.querySelector('.linea-producto');
            const input = fila.querySelector('.linea-cantidad');

            if (idProducto) {
                select.value = String(idProducto);
            }

            input.value = cantidad || 1;

            cuerpo.appendChild(fila);
            recalcular();
        }

        function quitarFila(fila) {
            if (cuerpo.querySelectorAll('tr').length === 1) {
                fila.querySelector('.linea-producto').value = '';
                fila.querySelector('.linea-cantidad').value = 1;
                recalcular();

                return;
            }

            fila.remove();
            recalcular();
        }

        document.getElementById('agregar-linea').addEventListener('click', function () {
            crearFila(0, 1);
        });

        cuerpo.addEventListener('input', function (evento) {
            if (evento.target.matches('.linea-cantidad')) {
                recalcular();
            }
        });

        cuerpo.addEventListener('change', function (evento) {
            if (evento.target.matches('.linea-producto, .linea-cantidad')) {
                recalcular();
            }
        });

        cuerpo.addEventListener('click', function (evento) {
            const boton = evento.target.closest('.quitar-linea');

            if (boton) {
                quitarFila(boton.closest('tr'));
            }
        });

        const lineasIniciales = <?= json_encode($lineasIniciales, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        if (Array.isArray(lineasIniciales) && lineasIniciales.length > 0) {
            lineasIniciales.forEach((linea) => crearFila(linea.id_producto, linea.cantidad));
        } else {
            crearFila(0, 1);
        }
    })();
</script>
</main>
</body>
</html>
 