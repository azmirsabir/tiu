<?php

namespace App\Traits;

trait CSVReader {

    public function read_csv_from_server_location($file_name){
        try{
            $dataArray=array();
            $row = 0;
            if (($handle = fopen(public_path('files'.'/'.$file_name), "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                    $num = count($data);

                    for ($col=0; $col < $num; $col++) {
                        $dataArray[$row][$col] = iconv(mb_detect_encoding($data[ $col], mb_detect_order(), true), "UTF-8", $data[ $col]);
                    }

                    $row++;
                }
                fclose($handle);
            }
            return $dataArray;
        }catch(\Exception $e){
            error_log("read csv trait error". "\r\n", 3, 'log.log');
            error_log($e->getMessage() . "\r\n", 3, 'log.log');
            return $dataArray;
        }
    }
}
