<?php

namespace App\Http\Controllers\Invoices;

use Illuminate\Http\Request;

interface InvoiceInterface
{	
  public function createInvoice(Request $request, $id);
}