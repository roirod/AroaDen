<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Exception;
use Lang;

trait BaseTrait {

    public function checkIfPaidIsHigher($units, $price, $paid)
    {   
        $total = (int)$units * (int)$price;

        if ( (int)$paid > (int)$total )
            return true;
    }

}