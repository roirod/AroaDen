<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Invoices\InvoiceInterface;
use App\Http\Controllers\InvoicesController;
use Illuminate\Http\Request;

class Complete extends InvoicesController implements InvoiceInterface
{
  public function __construct($invoices)
  {
    parent::__construct($invoices);
  }

  public function createInvoice(Request $request, $id)
  {




    
    return parent::create($request, $id);  
  }
  
}