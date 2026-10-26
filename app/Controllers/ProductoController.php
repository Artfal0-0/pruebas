<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use CodeIgniter\Controller;

class ProductoController extends Controller
{
    protected $productoModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
    }

    public function index()
    {
        $data['productos'] = $this->productoModel->findAll();
        return view('producto/index', $data);
    }

    public function create()
    {
        return view('producto/create');
    }

    public function store()
{
    $reglas = [
        'nombre' => 'required|min_length[2]|max_length[100]',
        'precio' => 'required|numeric|greater_than_equal_to[0]',
        'stock'  => 'required|integer|greater_than_equal_to[0]',
    ];

    $mensajes = [
        'nombre' => [
            'required'   => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede exceder los 100 caracteres',
        ],
        'precio' => [
            'required'               => 'El precio es obligatorio',
            'numeric'                => 'El precio solo puede contener números',
            'greater_than_equal_to'  => 'El precio no puede ser negativo',
        ],
        'stock' => [
            'required'               => 'El stock es obligatorio',
            'integer'                => 'El stock solo puede contener números enteros',
            'greater_than_equal_to'  => 'El stock no puede ser negativo',
        ],
    ];

    if (!$this->validate($reglas, $mensajes)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $datos = [
        'nombre' => $this->request->getPost('nombre'),
        'precio' => $this->request->getPost('precio'),
        'stock'  => $this->request->getPost('stock'),
    ];

    $this->productoModel->insert($datos);

    return redirect()->to('/producto')->with('success', 'Producto creado correctamente');
}

    public function delete($id)
    {
        $this->productoModel->delete($id);
        return redirect()->to('/producto');
    }
}
