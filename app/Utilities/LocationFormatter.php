<?php


namespace App\Utilities;


class LocationFormatter
{
    public static function generate_decimal_location_format($location)
    {
        $location = trim($location);

        if(str_contains($location,'°')){
            $location_array = explode('°',$location);
            $deg = (int)trim($location_array[0]);

            $location_array = explode('\'',trim($location_array[1]));
            $min = (int)trim($location_array[0]);

            $location_array = explode('"',trim($location_array[1]));
            $sec = (int)trim($location_array[0]);


//        $result =  $deg+( (( $min*60)+($sec) ) /3600 );
            return $deg + ($min / 60) + ($sec / 3600);
        }else{
            return $location;
        }

    }

    public static function get_distance_between_location($lat_a,$lon_a, $lat_b, $lon_b){
        $theta = $lon_a - $lon_b;
        $dist = sin(deg2rad($lat_a)) * sin(deg2rad($lat_b)) +  cos(deg2rad($lat_a)) * cos(deg2rad($lat_b)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        return $dist * 60 * 1.1515 * 1.609344 * 1000;

    }

}
