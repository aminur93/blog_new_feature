<?php
    /**
     * Created by PhpStorm.
     * User: pavel
     * Date: 5/7/2020
     * Time: 3:08 PM
     */
    
namespace App\Helpers;

class Helper{
    
    public static function date_convert($date)
    {
        $convert = date('d F Y',strtotime($date));
        
        return $convert;
    }
}