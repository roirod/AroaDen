<?php

namespace App\Models;

interface BaseModelInterface
{
	
    public function scopeFirstById($query, $id);

    public static function CountAll();

}