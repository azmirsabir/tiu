<?php


namespace App\Utilities;


use Illuminate\Support\Facades\DB;

class OracleEntries
{
    public static function get_date(){
        return DB::raw('to_date(\''.strtoupper(date('d-M-y h.i.s A')).'\', \'DD-MON-RR HH.MI.SS AM\')');
    }

    public static function clean_up_string($string){
        return trim(preg_replace( '/[\t\n\r\s]+/', ' ', strtolower($string)));
    }
}
