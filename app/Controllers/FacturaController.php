<?php

namespace App\Controllers;

use App\Models\FacturaModel;
use CodeIgniter\Controller;

class FacturaController extends Controller
{
    protected $facturaModel;
    protected $db;

    public function __construct()
    {
        $this->facturaModel = new FacturaModel();
        $this->db = \Config\Database::connect();
    }

    // Mostrar todas las facturas
    public function index()
    {
        $datos['facturas'] = $this->db->table('factura f')
            ->select(
                'f.id_factura,
                 f.fecha_emision,
                 f.total,
                 c.nombre AS nombre_cliente,
                 c.cedula AS cedula_cliente'
            )
            ->join('clientes c', 'c.id_cliente = f.id_cliente')
            ->orderBy('f.id_factura', 'DESC')
            ->get()
            ->getResultArray();

        return view('factura/index', $datos);
    }

    // Mostrar el formulario y guardar una factura nueva
    public function create()
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->guardar();
        }

        $datos['clientes'] = $this->db->table('clientes')
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResultArray();

        $datos['productos'] = $this->db->table('producto')
            ->where('stock >', 0)
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResultArray();

        return view('factura/create', $datos);
    }

    // Guardar la factura principal y sus detalles
    private function guardar()
    {
        $idCliente = (int) $this->request->getPost('id_cliente');
        $productos = $this->request->getPost('id_producto');
        $cantidades = $this->request->getPost('cantidad');

        if (
            $idCliente <= 0 ||
            !is_array($productos) ||
            !is_array($cantidades) ||
            empty($productos) ||
            count($productos) !== count($cantidades)
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Selecciona un cliente y agrega productos válidos.');
        }

        $cliente = $this->db->table('clientes')
            ->where('id_cliente', $idCliente)
            ->get()
            ->getRowArray();

        if (!$cliente) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El cliente seleccionado no existe.');
        }

        // Validar las cantidades y agrupar productos repetidos
        $cantidadesPorProducto = [];

        foreach ($productos as $indice => $idProducto) {
            $idProducto = filter_var(
                $idProducto,
                FILTER_VALIDATE_INT
            );

            $cantidad = filter_var(
                $cantidades[$indice],
                FILTER_VALIDATE_INT
            );

            if (
                $idProducto === false ||
                $idProducto <= 0 ||
                $cantidad === false ||
                $cantidad < 1
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Revisa los productos y sus cantidades.');
            }

            if (!isset($cantidadesPorProducto[$idProducto])) {
                $cantidadesPorProducto[$idProducto] = 0;
            }

            $cantidadesPorProducto[$idProducto] += $cantidad;
        }

        // Obtener precios reales y validar el stock
        $detalles = [];
        $totalFactura = 0;

        foreach ($cantidadesPorProducto as $idProducto => $cantidad) {
            $producto = $this->db->table('producto')
                ->where('id_producto', $idProducto)
                ->get()
                ->getRowArray();

            if (!$producto) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Uno de los productos no existe.');
            }

            if ($cantidad > (int) $producto['stock']) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Stock insuficiente para ' . $producto['nombre']
                    );
            }

            $precio = (float) $producto['precio'];
            $subtotal = round($precio * $cantidad, 2);

            $detalles[] = [
                'id_producto' => $idProducto,
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'subtotal' => $subtotal,
            ];

            $totalFactura += $subtotal;
        }

        $totalFactura = round($totalFactura, 2);

        // Guardar maestro y detalles en una sola transacción
        $this->db->transBegin();

        try {
            $this->facturaModel->insert([
                'id_cliente' => $idCliente,
                'fecha_emision' => date('Y-m-d H:i:s'),
                'total' => $totalFactura,
            ]);

            $idFactura = $this->facturaModel->getInsertID();

            if (!$idFactura) {
                throw new \RuntimeException(
                    'No se pudo crear la factura principal.'
                );
            }

            foreach ($detalles as $detalle) {
                $detalle['id_factura'] = $idFactura;

                $this->db->table('detalle_factura')
                    ->insert($detalle);
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException(
                    'No se pudieron guardar los detalles.'
                );
            }

            $this->db->transCommit();

            return redirect()->to(
                site_url('factura/detalle/' . $idFactura)
            )->with('mensaje', 'Factura creada correctamente.');

        } catch (\Throwable $error) {
            $this->db->transRollback();

            log_message(
                'error',
                'Error al crear factura: ' . $error->getMessage()
            );

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo guardar la factura.');
        }
    }

    // Consultar una factura con todos sus productos
    public function detalle($id)
    {
        $id = (int) $id;

        $factura = $this->db->table('factura f')
            ->select(
                'f.id_factura,
                 f.fecha_emision,
                 f.total,
                 c.nombre AS nombre_cliente,
                 c.cedula AS cedula_cliente,
                 c.telefono AS telefono_cliente'
            )
            ->join('clientes c', 'c.id_cliente = f.id_cliente')
            ->where('f.id_factura', $id)
            ->get()
            ->getRowArray();

        if (!$factura) {
            return redirect()->to(site_url('factura'))
                ->with('error', 'La factura no existe.');
        }

        $datos['factura'] = $factura;

        $datos['detalles'] = $this->db->table('detalle_factura d')
            ->select(
                'd.id_detalle,
                 d.cantidad,
                 d.precio_unitario,
                 d.subtotal,
                 p.nombre AS nombre_producto'
            )
            ->join('producto p', 'p.id_producto = d.id_producto')
            ->where('d.id_factura', $id)
            ->orderBy('d.id_detalle', 'ASC')
            ->get()
            ->getResultArray();

        return view('factura/detalle', $datos);
    }

    // Eliminar una factura y sus detalles asociados
    public function delete($id)
    {
        $id = (int) $id;

        $factura = $this->facturaModel->find($id);

        if (!$factura) {
            return redirect()->to(site_url('factura'))
                ->with('error', 'La factura no existe.');
        }

        if ($this->facturaModel->delete($id)) {
            return redirect()->to(site_url('factura'))
                ->with('mensaje', 'Factura eliminada correctamente.');
        }

        return redirect()->to(site_url('factura'))
            ->with('error', 'No se pudo eliminar la factura.');
    }
}
