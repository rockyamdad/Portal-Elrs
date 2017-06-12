<?php

namespace AppBundle\Util;

class PlaceHolders
{


    public static function  numberConvert($number) {

        $search_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        $replace_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
        return str_replace($search_array, $replace_array, $number);
    }

    public static function  numberConvertEnglish($number) {

        $search_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
        $replace_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        return str_replace($search_array, $replace_array, $number);
    }

}