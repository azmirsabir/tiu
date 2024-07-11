<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Ramsey\Uuid\Uuid;

trait CSVExporter
{
    public function export_array_data_to_csv($array_data, $export_columns, $file_name, $is_save = false)
    {
        try {
            $headers = array(
                'Content-Type' => 'application/txt',
                'Cache-Control' => 'must-revalidate',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Disposition' => 'attachment;',
            );

            $file_name = $file_name . '.csv';
            $handle = fopen($file_name, 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $cols=[];
            foreach ($export_columns as $col){
                array_push($cols,str_replace('_',' ',ucfirst($col)));
            }
            fputcsv($handle, $cols);

            foreach ($array_data as $key => $row) {
                fputcsv($handle, (array)$row);
            }

            fclose($handle);

            if ($is_save) {
                return $file_name;
            }

            return response()->download($file_name, $file_name, $headers)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            error_log("\n\r" . __FUNCTION__ . "::\n\r" . $e->getMessage() . "\n\r", 3, 'log.log');
            return ('failed_to_export');
        }
    }
}
