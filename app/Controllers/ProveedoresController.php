<?php

namespace App\Controllers;

use App\Models\ProveedoresModel;
use CodeIgniter\Controller;

class ProveedoresController extends Controller
{
    protected $proveedoresModel;

    public function __construct()
    {
        helper(['form']);
        $this->proveedoresModel = new ProveedoresModel();
    }

    // Reglas de validación del formulario de crear
    private function reglas(): array
    {
        return [
            'ruc' => [
                'rules'  => 'required|exact_length[13]|numeric|is_unique[proveedores.ruc]',
                'errors' => [
                    'required'     => 'El RUC es obligatorio.',
                    'exact_length' => 'El RUC debe tener 13 dígitos.',
                    'numeric'      => 'El RUC solo puede tener números.',
                    'is_unique'    => 'Ya existe un proveedor con ese RUC.',
                ],
            ],
            'ubicacion' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'La ubicación es obligatoria.',
                    'min_length' => 'La ubicación debe tener al menos 3 caracteres.',
                    'max_length' => 'La ubicación no puede pasar de 100 caracteres.',
                ],
            ],
            'telefono' => [
                'rules'  => 'required|numeric|min_length[7]|max_length[10]',
                'errors' => [
                    'required'   => 'El teléfono es obligatorio.',
                    'numeric'    => 'El teléfono solo puede tener números.',
                    'min_length' => 'El teléfono debe tener al menos 7 dígitos.',
                    'max_length' => 'El teléfono no puede pasar de 10 dígitos.',
                ],
            ],
        ];
    }

    // Deja los datos ordenados: sin espacios de más
    private function datosLimpios(): array
    {
        return [
            'ruc'       => trim($this->request->getPost('ruc')),
            'ubicacion' => trim(preg_replace('/\s+/', ' ', $this->request->getPost('ubicacion'))),
            'telefono'  => trim($this->request->getPost('telefono')),
        ];
    }

    // LISTAR
    public function index()
    {
        $data['proveedores'] = $this->proveedoresModel
            ->orderBy('id_proveedor', 'ASC')
            ->findAll();

        return view('proveedores/index', $data);
    }

    // Muestra el formulario de CREAR
    public function create()
    {
        return view('proveedores/create');
    }

    // GUARDAR un proveedor nuevo
    public function store()
    {
        if (! $this->validate($this->reglas())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->proveedoresModel->insert($this->datosLimpios());

        return redirect()->to(base_url('proveedores'))
            ->with('success', 'Proveedor registrado correctamente.');
    }

    // ELIMINAR
    public function delete($id)
    {
        $this->proveedoresModel->delete($id);

        return redirect()->to(base_url('proveedores'))
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}