<?php


namespace App\Utilities;


class FormatValidator
{
    public static function validate_msisdn_format($msisdn)
    {
        return (count(str_split($msisdn)) === 13 and substr($msisdn, 0, 4) === '9647');
    }

}
