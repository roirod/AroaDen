<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Exception;
use Redis;
use Lang;

trait BaseTrait {

    /**
     *  sanitize Data / convert to html entities
     * 
     *  @param string|int $data
     *  @return string       
     */
    protected function sanitizeData($data)
    {   
        $data = trim($data);
        $data = htmlentities($data, ENT_QUOTES, "UTF-8");

        return $data;
    }

    /**
     *  redirect If Id Is Null
     * 
     *  @param int $int
     *  @param string $route
     *  @return object       
     */
    protected function redirectIfIdIsNull($id, $route)
    {   
        if ( is_null($id) )
            return redirect($route);
    }

    /**
     *  echo Json Ouptut, used in ajax response
     * 
     *  @param $data string  
     */
    protected function echoJsonOuptut($data)
    {   
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();    
    }

    /**
     *  format Number
     * 
     *  @param int $num
     *  @return int       
     */
    protected function formatNumber($num)
    {   
        return number_format($num, 0, '', '.');
    }

    /**
     *  convert date Y m d To D m Y
     * 
     *  @param string $date
     *  @return string       
     */
    protected function convertYmdToDmY($date)
    {   
        $date = date('d-m-Y', strtotime($date));

        return $date;
    }

    /**
     *  validate Date
     * 
     *  @param string $date
     *  @return bool
     */
    protected function validateDate($date)
    {   
        list($y, $m, $d) = array_pad(explode('-', $date, 3), 3, 0);

        return ctype_digit("$y$m$d") && checkdate($m, $d, $y);
    }

    /**
     *  validate Time
     * 
     *  @param string $time
     *  @return bool   
     */
    protected function validateTime($time)
    {   
        if ( preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time) )
            return true;

        return false;
    }

    /**
     *  get Settings from redis
     *
     *  @return object   
     */
    protected function getSettings()
    {   
        return json_decode(Redis::get('settings'));
    }

    /**
     *  check If Paid Is Higher
     * 
     *  @param int $units
     *  @param int $price
     *  @param int $paid
     *  @return bool       
     */
    protected function checkIfPaidIsHigher($units, $price, $paid)
    {   
        $total = (int)$units * (int)$price;

        if ( (int)$paid > (int)$total )
            return true;
    }

}