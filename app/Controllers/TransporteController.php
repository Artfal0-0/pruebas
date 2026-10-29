<?php

namespace App\Controllers;

use App\Models\TransporteModel;

class TransporteController extends BaseController
{
    protected $transporteModel;

    public function __construct()
    {
        $this->transporteModel = new TransporteModel();
    }

    public function index()
    {
        $data['transportes'] = $this->transporteModel->findAll();

        return view('transporte/index', $data);
    }

    public function create()
    {
        return view('transporte/create');
    }

    public function store()
    {
        // guardar aplicando validaciones automáticas del modelo
        $saved = $this->transporteModel->save([
            'nombre_empresa'    => $this->request->getPost('nombre_empresa'),
            'tipo_vehiculo'     => $this->request->getPost('tipo_vehiculo'),
            'telefono_contacto' => $this->request->getPost('telefono_contacto'),
        ]);

        // validación falla, regresa al formulario con los errores
        if (!$saved) {
            return redirect()->back()->withInput()->with('errors', $this->transporteModel->errors());
        }

        return redirect()->to(base_url('transporte'))->with('success', 'Transporte registrado exitosamente.');
    }

    public function delete($id = null)
    {
        if ($id !== null) {
            $this->transporteModel->delete($id);
        }

        return redirect()->to(base_url('transporte'))->with('success', 'Transporte eliminado exitosamente.');
    }
}