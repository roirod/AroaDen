<?php

namespace App\Http\Controllers\Facturas;

use Illuminate\Http\Request;

interface InvoiceInterface
{	
    public function createInvoice($request, $id);
}