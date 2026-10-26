<?php

namespace App\Models;

use CodeIgniter\Model;

class FacturaModel extends Model
{
    protected $table = 'factura';
    protected $primaryKey = 'id_factura';

    protected $allowedFields = [
        'id_cliente',
        'id_producto',
        'cantidad',
        'fecha_emision',
        'total',
    ];

    public function getFacturas(): array
    {
        return $this->db->table('factura')
            ->select('factura.id_factura, factura.cantidad, factura.fecha_emision, factura.total, clientes.nombre AS cliente_nombre, clientes.cedula AS cliente_cedula')
            ->join('clientes', 'clientes.id_cliente = factura.id_cliente', 'left')
            ->orderBy('factura.id_factura', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getFactura(int $idFactura): ?array
    {
        $factura = $this->db->table('factura')
            ->select('factura.id_factura, factura.cantidad, factura.fecha_emision, factura.total, clientes.id_cliente, clientes.nombre AS cliente_nombre, clientes.cedula AS cliente_cedula, clientes.telefono AS cliente_telefono')
            ->join('clientes', 'clientes.id_cliente = factura.id_cliente', 'left')
            ->where('factura.id_factura', $idFactura)
            ->get()
            ->getRowArray();

        return $factura ?? null;
    }

    public function getDetalle(int $idFactura): array
    {
        return $this->db->table('detalle_factura')
            ->select('detalle_factura.id_detalle, detalle_factura.cantidad, detalle_factura.precio_unitario, detalle_factura.subtotal, producto.nombre AS producto_nombre')
            ->join('producto', 'producto.id_producto = detalle_factura.id_producto', 'left')
            ->where('detalle_factura.id_factura', $idFactura)
            ->orderBy('detalle_factura.id_detalle', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function crearFactura(int $idCliente, array $lineas): ?int
    {
        if ($idCliente <= 0) {
            return null;
        }

        $cantidades = [];
        foreach ($lineas as $linea) {
            $idProducto = (int) ($linea['id_producto'] ?? 0);
            $cantidad   = (int) ($linea['cantidad'] ?? 0);

            if ($idProducto <= 0 || $cantidad <= 0) {
                continue;
            }

            $cantidades[$idProducto] = ($cantidades[$idProducto] ?? 0) + $cantidad;
        }

        if ($cantidades === []) {
            return null;
        }

        $clienteExiste = $this->db->table('clientes')
            ->where('id_cliente', $idCliente)
            ->countAllResults() > 0;

        if (! $clienteExiste) {
            return null;
        }

        $productos = $this->db->table('producto')
            ->whereIn('id_producto', array_keys($cantidades))
            ->get()
            ->getResultArray();

        if (count($productos) !== count($cantidades)) {
            return null;
        }

        $precios = [];
        foreach ($productos as $producto) {
            $precios[(int) $producto['id_producto']] = (float) $producto['precio'];
        }

        $detalle       = [];
        $total         = 0.0;
        $cantidadTotal = 0;

        foreach ($cantidades as $idProducto => $cantidad) {
            $subtotal    = round($precios[$idProducto] * $cantidad, 2);
            $total      += $subtotal;
            $cantidadTotal += $cantidad;

            $detalle[] = [
                'id_producto'     => $idProducto,
                'cantidad'        => $cantidad,
                'precio_unitario' => $precios[$idProducto],
                'subtotal'        => $subtotal,
            ];
        }

        $this->db->transBegin();

        try {
            $idFactura = (int) $this->insert([
                'id_cliente'    => $idCliente,
                'id_producto'   => $detalle[0]['id_producto'],
                'cantidad'      => $cantidadTotal,
                'fecha_emision' => date('Y-m-d H:i:s'),
                'total'         => round($total, 2),
            ]);

            if ($idFactura === 0) {
                throw new \RuntimeException('No se pudo guardar la cabecera de la factura.');
            }

            foreach ($detalle as $fila) {
                $fila['id_factura'] = $idFactura;
                $this->db->table('detalle_factura')->insert($fila);
            }

            $this->db->transCommit();

            return $idFactura;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error al crear la factura: ' . $e->getMessage());

            return null;
        }
    }

    public function eliminarFactura(int $idFactura): bool
    {
        $this->db->transBegin();

        try {
            $this->db->table('detalle_factura')->where('id_factura', $idFactura)->delete();
            $this->db->table('factura')->where('id_factura', $idFactura)->delete();
            $this->db->transCommit();

            return true;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Error al eliminar la factura: ' . $e->getMessage());

            return false;
        }
    }
}
