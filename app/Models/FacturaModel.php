<?php

namespace App\Models;

use CodeIgniter\Model;

class FacturaModel extends Model
{
    // Tabla principal de facturas
    protected $table = 'factura';

    protected $primaryKey = 'id_factura';

    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_cliente',
        'fecha_emision',
        'total',
    ];
    protected $useTimestamps = false;
}
