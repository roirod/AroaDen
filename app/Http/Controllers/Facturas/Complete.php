<?php

namespace App\Http\Controllers\Facturas;

use App\Http\Controllers\FacturasController;
use App\Http\Controllers\Facturas\InvoiceInterface;

class Complete extends FacturasController implements InvoiceInterface
{
    public function __construct($factu)
    {
        parent::__construct($factu);
    }

    public function createInvoice($request, $id)
    {




        return parent::create($request, $id);  
    }
}