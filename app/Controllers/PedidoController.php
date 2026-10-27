<?php

namespace App\Controllers;

use App\Models\PedidoModel;
use CodeIgniter\Controller;

class PedidoController extends Controller
{
    protected $pedidoModel;

    public function __construct()
    {
        $this->pedidoModel = new PedidoModel();
    }

    // Listar pedidos
    public function index()
    {
        $data['pedidos'] = $this->pedidoModel->findAll();

        return view('pedido/index', $data);
    }

    // Mostrar formulario para crear pedidos
    public function create()
    {
        return view('pedido/create');
    }

    // Guardar un nuevo pedido
    public function store()
    {
        $datos = $this->request->getPost([
            'descripcion',
            'estado'
        ]);

        if (!$this->validate([
            'descripcion' => 'required',
            'estado' => 'required|in_list[Pendiente,En proceso,Completado,Cancelado]'
        ])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->pedidoModel->insert($datos);

        return redirect()->to('/pedido')
            ->with('success', 'Pedido registrado correctamente');
    }

    // Eliminar pedido
    public function delete($id)
    {
        $pedido = $this->pedidoModel->find($id);

        if (!$pedido) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->pedidoModel->delete($id);

        return redirect()->to('/pedido')
            ->with('success', 'Pedido eliminado correctamente');
    }
}
