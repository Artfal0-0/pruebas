<?php

namespace App\Controllers;

use App\Models\ClientesModel;
use CodeIgniter\Controller;

class ClientesController extends Controller
{
    protected $clientesModel;

    public function __construct()
    {
        $this->clientesModel = new ClientesModel();
    }

    // Reglas de validación. Si se pasa un $id, la cédula puede repetirse (es el mismo registro).
    private function reglas($id = null): array
    {
        $unique = ($id === null)
            ? 'is_unique[clientes.cedula]'
            : 'is_unique[clientes.cedula,id_cliente,' . $id . ']';

        return [
            'nombre' => [
                'rules'  => 'required|min_length[2]|max_length[100]|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/u]',
                'errors' => [
                    'required'    => 'El nombre es obligatorio.',
                    'min_length'  => 'El nombre debe tener al menos 2 caracteres.',
                    'max_length'  => 'El nombre no puede exceder los 100 caracteres.',
                    'regex_match' => 'El nombre solo puede contener letras.',
                ],
            ],
            'cedula' => [
                'rules'  => 'required|regex_match[/^[0-9]{10}$/]|' . $unique,
                'errors' => [
                    'required'    => 'La cédula es obligatoria.',
                    'regex_match' => 'La cédula solo puede contener números y debe tener 10 dígitos.',
                    'is_unique'   => 'Ya existe un cliente con esa cédula.',
                ],
            ],
            'telefono' => [
                'rules'  => 'required|regex_match[/^[0-9]{10}$/]',
                'errors' => [
                    'required'    => 'El teléfono es obligatorio.',
                    'regex_match' => 'El teléfono solo puede contener números y debe tener 10 dígitos.',
                ],
            ],
        ];
    }

    public function index()
    {
        $data['clientes'] = $this->clientesModel->findAll();
        return view('clientes/index', $data);
    }

    public function create()
    {
        return view('clientes/create');
    }

    public function store()
    {
        $datos = $this->request->getPost([
            'nombre',
            'cedula',
            'telefono',
        ]);

        if (!$this->validate($this->reglas())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->clientesModel->insert($datos);

        return redirect()->to('/clientes')
            ->with('success', 'Cliente registrado correctamente');
    }

    public function edit($id)
    {
        $cliente = $this->clientesModel->find($id);

        if (!$cliente) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['cliente'] = $cliente;
        return view('clientes/edit', $data);
    }

    public function update($id)
    {
        $datos = $this->request->getPost([
            'nombre',
            'cedula',
            'telefono',
        ]);

        if (!$this->validate($this->reglas($id))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->clientesModel->update($id, $datos);

        return redirect()->to('/clientes')
            ->with('success', 'Cliente actualizado correctamente');
    }

    public function delete($id)
    {
        $this->clientesModel->delete($id);

        return redirect()->to('/clientes');
    }
}
