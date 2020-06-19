<?php

namespace App\Http\Controllers\Traits;

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
   *  sanitize Data / convert to html entities
   * 
   *  @param string|int $data
   *  @return string       
   */
  protected function sanitizeRequest($data)
  {
    if (isset($data["_token"]))
      unset($data["_token"]);

    foreach ($data as $key => $val) {
      if (is_array($data[$key]))
        continue;

      $data[$key] = $this->sanitizeData($data[$key]);
    }

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
    if ( is_null($id) || (is_numeric($id) && $id > 0 && $id == round($id)) )
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

  protected function formatCurrency($num)
  {
    return number_format($num, $this->Alocale["frac_digits"], $this->Alocale["decimal_point"], $this->Alocale["thousands_sep"]);
  }

  protected function formatCurrencyDB($num)
  {
    $currency = $this->config['currency'];

    $str = str_replace($this->Alocale["thousands_sep"], $currency["db_thousands_sep"], $num);
    $res = str_replace($this->Alocale["decimal_point"], $currency["db_dec_point"], $str);
    
    return $res;
  }

  /**
   *  format Number
   * 
   *  @param int $num
   *  @return int       
   */
  protected function formatNumber($num)
  {   
    return number_format($num, 2, '.', '.');
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
   *  convert date Y m d To D m Y
   * 
   *  @param string $date
   *  @return string       
   */
  protected function convertDmYToYmd($date)
  {
    return date('Y-m-d', strtotime($date));
  }

  /**
   *  validate Date
   * 
   *  @param string $date
   *  @return bool
   */
  protected function validateDateDDMMYYYY($date)
  {   
    list($d, $m, $y) = array_pad(explode('-', $date, 3), 3, 0);

    return ctype_digit("$y$m$d") && checkdate($m, $d, $y);
  }

  /**
   *  validate Date
   * 
   *  @param string $date
   *  @return bool
   */
  protected function validateDateYYYYMMDD($date)
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
   *  check If Paid Is Higher
   * 
   *  @param int $units
   *  @param int $price
   *  @param int $paid
   *  @return bool       
   */
  protected function checkIfPaidIsHigher($units, $price, $paid)
  {   
    $total = $units * $price;
    $total = number_format($total, 2, '.', '');

    if ( $paid > $total )
      return true;
  }

  protected function calcTotalTax($price, $tax)
  {
    $total = (($price * $tax) / 100) + $price;

    return $total;
  }

}