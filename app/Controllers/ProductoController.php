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
        $nombre = esc($this->request->getPost('nombre'), 'html');
        $precio = esc($this->request->getPost('precio'), 'html');
        $stock  = esc($this->request->getPost('stock'), 'html');

        $precioFormateado = str_replace(',', '.', $precio);

        $datosParaValidar = [
            'nombre' => $nombre,
            'precio' => $precioFormateado,      
            'stock'  => $stock,
        ];

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
                'required'              => 'El precio es obligatorio',
                'numeric'               => 'El precio solo puede contener números (puede usar punto o coma)',
                'greater_than_equal_to' => 'El precio no puede ser negativo',
            ],
            'stock' => [
                'required'              => 'El stock es obligatorio',
                'integer'               => 'El stock solo puede contener números enteros',
                'greater_than_equal_to' => 'El stock no puede ser negativo',
            ],
        ];

        if (!$this->validateData($datosParaValidar, $reglas, $mensajes)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->productoModel->insert($datosParaValidar);

        return redirect()->to('/producto')->with('success', 'Producto creado correctamente');
    }

    public function delete($id)
    {
        $this->productoModel->delete($id);
        return redirect()->to('/producto');
    }
}
