<?php

namespace App\Models;

use CodeIgniter\Model;

class TransporteModel extends Model
{
    protected $table            = 'transporte';
    protected $primaryKey       = 'id_transporte';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'nombre_empresa',
        'tipo_vehiculo',
        'telefono_contacto',
    ];

    // validaciones
    protected $validationRules = [
        'nombre_empresa'    => 'required|alpha_space|min_length[3]|max_length[100]',
        'tipo_vehiculo'     => 'required|alpha_space|min_length[3]|max_length[50]',
        'telefono_contacto' => 'required|numeric|min_length[7]|max_length[20]',
    ];

    protected $validationMessages = [
        'nombre_empresa' => [
            'required'    => 'El nombre de la empresa es obligatorio.',
            'alpha_space' => 'El nombre de la empresa solo debe contener letras y espacios (sin números ni símbolos).',
        ],
        'tipo_vehiculo' => [
            'required'    => 'El tipo de vehículo es obligatorio.',
            'alpha_space' => 'El tipo de vehículo solo debe contener letras y espacios (sin números ni símbolos).',
        ],
        'telefono_contacto' => [
            'required' => 'El teléfono de contacto es obligatorio.',
            'numeric'  => 'El teléfono de contacto debe contener únicamente números.',
        ],
    ];//
}   