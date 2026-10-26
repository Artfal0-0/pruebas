<?php helper('url'); ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Proyecto CRUD</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #1f2937;
            background: #f3f4f6;
            font-family: Arial, sans-serif;
        }

        .site-header {
            width: 100%;
            padding: 20px;
            color: #fff;
            background: #172554;
        }

        .main-nav {
            display: flex;
            gap: 10px;
        }

        .nav-group a {
            display: inline-block;
            padding: 9px 12px;
            border-radius: 7px;
            color: #e0e7ff;
            text-decoration: none;
        }

        .nav-group a:hover,
        .nav-group a:focus-visible {
            color: #172554;
            background: #fff;
        }

        main {
            width: min(100% - 32px, 1328px);
            margin: 32px auto;
        }

        .page-header {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h1 {
            margin: 0;
        }

        .page-subtitle {
            margin: 6px 0 0;
            color: #6b7280;
        }

        .card {
            margin-bottom: 24px;
            padding: 24px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(23, 37, 84, 0.10);
        }

        .card h2 {
            margin: 0 0 16px;
            font-size: 20px;
            color: #172554;
        }

        .alert {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-success {
            color: #166534;
            background: #dcfce7;
        }

        .alert-error {
            color: #991b1b;
            background: #fee2e2;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 16px;
            border: 0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 13px;
        }

        .btn-primary {
            color: #fff;
            background: #172554;
        }

        .btn-primary:hover {
            background: #1e3a8a;
        }

        .btn-secondary {
            color: #172554;
            background: #e0e7ff;
        }

        .btn-secondary:hover {
            background: #c7d2fe;
        }

        .btn-danger {
            color: #991b1b;
            background: #fee2e2;
        }

        .btn-danger:hover {
            background: #fecaca;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            color: #172554;
            background: #eef2ff;
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .actions {
            display: flex;
            gap: 8px;
            white-space: nowrap;
        }

        .actions form {
            margin: 0;
        }

        .muted {
            display: block;
            color: #6b7280;
            font-size: 12px;
        }

        .empty {
            margin: 0;
            color: #6b7280;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .form-field label {
            color: #374151;
            font-size: 13px;
            font-weight: 600;
        }

        .form-field select,
        .form-field input {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            color: #1f2937;
            background: #fff;
            font-family: inherit;
            font-size: 14px;
        }

        .form-field select:focus,
        .form-field input:focus {
            border-color: #172554;
            outline: 2px solid #c7d2fe;
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .qty-input {
            width: 96px;
        }

        .total-line {
            display: flex;
            justify-content: flex-end;
            align-items: baseline;
            gap: 16px;
            margin-top: 16px;
            font-size: 15px;
        }

        .total-line strong {
            color: #172554;
            font-size: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .info-item span {
            display: block;
            margin-bottom: 4px;
            color: #6b7280;
            font-size: 12px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .info-item strong {
            font-size: 15px;
        }

        @media (max-width: 600px) {
            .actions {
                flex-direction: column;
            }
        }

    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-content">
            <nav class="main-nav" aria-label="Navegación principal">
                <div class="nav-group">
                    <a href="<?= base_url('transporte') ?>">Transportes</a>
                </div>
                <div class="nav-group">
                    <a href="<?= base_url('pedido') ?>">Pedidos</a>
                </div>
                <div class="nav-group">
                    <a href="<?= base_url('vehiculos') ?>">Vehículos</a>
                </div>
                <div class="nav-group">
                    <a href="<?= base_url('proveedores') ?>">Proveedores</a>
                </div>
                <div class="nav-group">
                    <a href="<?= base_url('clientes') ?>">Clientes</a>
                </div>
                <div class="nav-group">
                    <a href="<?= base_url('producto') ?>">Productos</a>
                </div>
                <div class="nav-group">
                    <a href="<?= base_url('factura') ?>">Facturas</a>
                </div>
            </nav>
        </div>
    </header>
    <main>
