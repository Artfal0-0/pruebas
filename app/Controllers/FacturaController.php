<?php

namespace App\Controllers;

use App\Models\ClientesModel;
use App\Models\FacturaModel;
use App\Models\ProductoModel;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;

class FacturaController extends Controller
{
    protected $facturaModel;

    public function __construct()
    {
        $this->facturaModel = new FacturaModel();
    }

    public function index()
    {
        return view('factura/index', [
            'facturas' => $this->facturaModel->getFacturas(),
        ]);
    }

    public function create()
    {
        return view('factura/create', [
            'clientes'  => (new ClientesModel())->orderBy('nombre', 'ASC')->findAll(),
            'productos' => (new ProductoModel())->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $idCliente  = (int) $this->request->getPost('id_cliente');
        $productos  = $this->request->getPost('productos');
        $cantidades = $this->request->getPost('cantidades');

        $productos  = is_array($productos) ? $productos : [];
        $cantidades = is_array($cantidades) ? $cantidades : [];

        $lineas = [];
        foreach ($productos as $indice => $idProducto) {
            $lineas[] = [
                'id_producto' => (int) $idProducto,
                'cantidad'    => (int) ($cantidades[$indice] ?? 0),
            ];
        }

        $idFactura = $this->facturaModel->crearFactura($idCliente, $lineas);

        if ($idFactura === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No se pudo crear la factura. Selecciona un cliente y agrega al menos un producto con cantidad válida.');
        }

        return redirect()
            ->to(base_url('factura/detalle/' . $idFactura))
            ->with('success', 'Factura creada correctamente.');
    }

    public function detalle($id)
    {
        $idFactura = (int) $id;
        $factura   = $this->facturaModel->getFactura($idFactura);

        if ($factura === null) {
            throw PageNotFoundException::forPageNotFound('La factura no existe.');
        }

        return view('factura/detalle', [
            'factura' => $factura,
            'detalle' => $this->facturaModel->getDetalle($idFactura),
        ]);
    }

    public function delete($id)
    {
        if (! $this->facturaModel->eliminarFactura((int) $id)) {
            return redirect()
                ->to(base_url('factura'))
                ->with('error', 'No se pudo eliminar la factura.');
        }

        return redirect()
            ->to(base_url('factura'))
            ->with('success', 'Factura eliminada correctamente.');
    }
}
