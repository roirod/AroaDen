<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\Invoices\InvoiceInterface;

class Complete extends InvoicesController implements InvoiceInterface
{
    public function __construct($invoices)
    {
        parent::__construct($invoices);
    }

    public function createInvoice($request, $id)
    {




        return parent::create($request, $id);  
    }
}