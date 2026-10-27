<?= $this->include('layouts/header') ?>

<div class="container mt-4 mb-5">

    <div class="mb-4">
        <h1>Crear factura</h1>
        <p class="text-muted">
            Selecciona el cliente y agrega los productos que deseas facturar.
        </p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($clientes)): ?>

        <div class="alert alert-warning">
            No hay clientes registrados. Primero debes registrar un cliente.
        </div>

    <?php elseif (empty($productos)): ?>

        <div class="alert alert-warning">
            No hay productos con stock disponible para facturar.
        </div>

    <?php else: ?>

        <form action="<?= site_url('factura/create') ?>"
              method="post"
              id="formularioFactura">

            <?= csrf_field() ?>

            <!-- Datos principales de la factura -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Datos de la factura</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">

                            <label for="id_cliente" class="form-label">
                                Cliente
                            </label>

                            <select name="id_cliente"
                                    id="id_cliente"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Selecciona un cliente
                                </option>

                                <?php foreach ($clientes as $cliente): ?>
                                    <option
                                        value="<?= esc($cliente['id_cliente']) ?>"
                                        <?= old('id_cliente') == $cliente['id_cliente']
                                            ? 'selected'
                                            : '' ?>>

                                        <?= esc($cliente['nombre']) ?>
                                        - <?= esc($cliente['cedula']) ?>

                                    </option>
                                <?php endforeach; ?>

                            </select>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalles de la factura -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Productos de la factura</h5>

                    <button type="button"
                            class="btn btn-success btn-sm"
                            id="agregarProducto">
                        + Agregar producto
                    </button>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 250px;">Producto</th>
                                    <th style="min-width: 110px;">Precio</th>
                                    <th style="min-width: 100px;">Cantidad</th>
                                    <th style="min-width: 120px;">Subtotal</th>
                                    <th style="width: 90px;">Acción</th>
                                </tr>
                            </thead>

                            <tbody id="listaProductos">
                                <!-- Las filas se agregan con JavaScript -->
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">
                                        TOTAL:
                                    </th>

                                    <th>
                                        <span id="totalFactura">$0.00</span>
                                    </th>

                                    <th></th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>

                    <small class="text-muted">
                        El total se calcula automáticamente según el precio
                        y la cantidad de cada producto.
                    </small>

                </div>
            </div>

            <!-- Botones del formulario -->
            <div class="d-flex justify-content-end gap-2">

                <a href="<?= site_url('factura') ?>"
                   class="btn btn-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    Guardar factura
                </button>

            </div>

        </form>

    <?php endif; ?>

</div>

<!-- Plantilla para crear las filas de productos -->
<template id="plantillaProducto">
    <tr class="fila-producto">

        <td>
            <select name="id_producto[]"
                    class="form-select seleccionar-producto"
                    required>

                <option value="">Selecciona un producto</option>

                <?php foreach ($productos as $producto): ?>
                    <option
                        value="<?= esc($producto['id_producto']) ?>"
                        data-precio="<?= esc($producto['precio']) ?>"
                        data-stock="<?= esc($producto['stock']) ?>">

                        <?= esc($producto['nombre']) ?>
                        - $<?= number_format(
                            (float) $producto['precio'],
                            2,
                            '.',
                            ','
                        ) ?>
                        (Stock: <?= esc($producto['stock']) ?>)

                    </option>
                <?php endforeach; ?>

            </select>
        </td>

        <td>
            <span class="precio-producto">$0.00</span>
        </td>

        <td>
            <input type="number"
                   name="cantidad[]"
                   class="form-control cantidad-producto"
                   min="1"
                   step="1"
                   value="1"
                   required>
        </td>

        <td>
            <strong class="subtotal-producto">$0.00</strong>
        </td>

        <td>
            <button type="button"
                    class="btn btn-danger btn-sm eliminar-producto">
                Quitar
            </button>
        </td>

    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const listaProductos = document.getElementById('listaProductos');
    const plantillaProducto = document.getElementById('plantillaProducto');
    const botonAgregar = document.getElementById('agregarProducto');
    const formulario = document.getElementById('formularioFactura');
    const totalFactura = document.getElementById('totalFactura');

    // Formatear cantidades monetarias
    function formatoDinero(valor) {
        return '$' + valor.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Obtener los datos del producto seleccionado
    function obtenerDatosProducto(fila) {
        const selector = fila.querySelector('.seleccionar-producto');
        const opcion = selector.options[selector.selectedIndex];

        if (!selector.value) {
            return { precio: 0, stock: 0 };
        }

        return {
            precio: Number(opcion.dataset.precio),
            stock: Number(opcion.dataset.stock)
        };
    }

    // Actualizar precio y subtotal de una fila
    function actualizarFila(fila) {
        const datos = obtenerDatosProducto(fila);
        const campoCantidad = fila.querySelector('.cantidad-producto');

        const cantidad = Number(campoCantidad.value);
        const cantidadValida = Number.isInteger(cantidad) && cantidad > 0;

        fila.querySelector('.precio-producto').textContent =
            formatoDinero(datos.precio);

        fila.querySelector('.subtotal-producto').textContent =
            formatoDinero(
                cantidadValida ? datos.precio * cantidad : 0
            );

        campoCantidad.max = datos.stock;

        if (datos.stock > 0 && cantidad > datos.stock) {
            campoCantidad.setCustomValidity(
                'La cantidad supera el stock disponible (' +
                datos.stock + ').'
            );
        } else if (
            datos.stock <= 0 &&
            fila.querySelector('.seleccionar-producto').value
        ) {
            campoCantidad.setCustomValidity(
                'El producto no tiene stock disponible.'
            );
        } else {
            campoCantidad.setCustomValidity('');
        }

        calcularTotal();
    }

    // Sumar todos los subtotales
    function calcularTotal() {
        let total = 0;

        listaProductos.querySelectorAll('.fila-producto')
            .forEach(function (fila) {
                const datos = obtenerDatosProducto(fila);
                const cantidad = Number(
                    fila.querySelector('.cantidad-producto').value
                );

                if (Number.isInteger(cantidad) && cantidad > 0) {
                    total += datos.precio * cantidad;
                }
            });

        totalFactura.textContent = formatoDinero(total);
    }

    // Añadir una nueva fila
    function agregarFilaProducto() {
        const nuevaFila = plantillaProducto.content
            .firstElementChild.cloneNode(true);

        listaProductos.appendChild(nuevaFila);
        actualizarFila(nuevaFila);
    }

    botonAgregar.addEventListener('click', function () {
        agregarFilaProducto();
    });

    // Reaccionar a cambios de producto o cantidad
    listaProductos.addEventListener('change', function (evento) {
        if (
            evento.target.classList.contains('seleccionar-producto') ||
            evento.target.classList.contains('cantidad-producto')
        ) {
            const fila = evento.target.closest('.fila-producto');
            actualizarFila(fila);
        }
    });

    listaProductos.addEventListener('input', function (evento) {
        if (evento.target.classList.contains('cantidad-producto')) {
            actualizarFila(evento.target.closest('.fila-producto'));
        }
    });

    // Eliminar una fila de producto
    listaProductos.addEventListener('click', function (evento) {
        if (evento.target.classList.contains('eliminar-producto')) {
            evento.target.closest('.fila-producto').remove();
            calcularTotal();
        }
    });

    // Validar antes de guardar la factura
    formulario.addEventListener('submit', function (evento) {
        const filas = listaProductos.querySelectorAll('.fila-producto');

        if (filas.length === 0) {
            evento.preventDefault();
            alert('Debes agregar al menos un producto.');
            return;
        }

        const productosSeleccionados = [];

        for (const fila of filas) {
            const selector = fila.querySelector('.seleccionar-producto');
            const campoCantidad = fila.querySelector('.cantidad-producto');

            if (!selector.value) {
                evento.preventDefault();
                alert('Selecciona un producto en cada fila.');
                selector.focus();
                return;
            }

            if (productosSeleccionados.includes(selector.value)) {
                evento.preventDefault();
                alert(
                    'No repitas el mismo producto en varias filas. ' +
                    'Modifica la cantidad de la fila existente.'
                );
                selector.focus();
                return;
            }

            productosSeleccionados.push(selector.value);

            const datos = obtenerDatosProducto(fila);
            const cantidad = Number(campoCantidad.value);

            if (
                !Number.isInteger(cantidad) ||
                cantidad < 1 ||
                cantidad > datos.stock
            ) {
                evento.preventDefault();
                alert('Revisa la cantidad y el stock de los productos.');
                campoCantidad.focus();
                return;
            }
        }
    });

    // Comenzar con una fila de producto
    agregarFilaProducto();

});
</script>

</main>
</body>
</html>