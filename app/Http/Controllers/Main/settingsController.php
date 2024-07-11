<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\SettingsModel;
use App\Models\User;
use App\program_array;
use App\Traits\CSVExporter;
use App\Traits\CSVReader;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class settingsController extends Controller
{
    use CSVExporter, CSVReader;
    public function index(Request $request){
        return view('settings.settings',['data'=>$request->all()]);
    }
    public function get_data(Request $request){
        return SettingsModel::getSettingsData($request);
    }
    public function save_data(Request $request){
        $table_name=self::get_table_name($request->section);
        $data=array_merge($request->all(), ['u_id' => Auth::user()->id]);
        unset($data['section']);
        try {
            if($request->id){
                if(!SettingsModel::check_if_same_exist($request)){
                    $update=SettingsModel::update_function($table_name,$request->id,$data);
                    if($update) return ['status'=>"success",'message'=>'Successfully updated'];
                    else return ['status'=>"error",'message'=>'Update failed'];
                }else{
                    return ['status'=>"error",'message'=>'Already exist!'];
                }

            }else{
                    $res=SettingsModel::check_if_same_exist($request);
                    if($res['status']==0){
                        $insert=SettingsModel::save_function($table_name,$data);
                        if($insert) return ['status'=>"success",'message'=>'Successfully inserted'];
                        else return ['status'=>"error",'message'=>'Insert failed'];
                    }elseif($res['status']==2){
                        //update the existing record
                        $data=array_merge($data, ['status' => 1]);
                        if(SettingsModel::update_function($table_name,$res['id'],$data)){
                            return ['status'=>"success",'message'=>'Successfully inserted'];
                        }
                        return ['status'=>"error",'message'=>'Insert failed'];
                    }else{
                        return ['status'=>"error",'message'=>'Record already exist!'];
                    }
            }
        }catch (Exception $e){
            Log::error(__FUNCTION__ . " : " . __CLASS__ . ' : '.$e->getMessage());
            return ['status'=>"error",'message'=>'Failed'];
        }
    }
    public function delete_data(Request $request){
        $table_name=self::get_table_name($request->section);
        try {
            if(SettingsModel::delete_function($table_name,$request->id))
                return ['status'=>"success",'message'=>'Successfully deleted'];
            else
                return ['status'=>"error",'message'=>'Delete failed'];
        }catch (Exception $e){
            Log::error(__FUNCTION__ . " : " . __CLASS__ . ' : '.$e->getMessage());
            return ['status'=>"error",'message'=>'Delete failed'];
        }
    }
    public static function get_table_name($section){
        if($section==="transaction_sub_types"){
            return "transaction_sub_types";
        }
        return program_array::program_array()['settings'][$section]['db_table'];
    }
    public function export_settings(Request $request){
        $data=SettingsModel::export($request);
        return $this->export_array_data_to_csv($data, array_keys(get_object_vars($data[0])), 'file');
    }
    public function get_sub_types(Request $request){
        if($request->type_id)
            return SettingsModel::get_transaction_sub_type($request);
        else
            return ['Error'=>"Type id not provided"];
    }
    public function delete_sub_types(Request $request){
        if($request->id)
            return SettingsModel::delete_transaction_sub_type($request->id);
        else
            return ['Error'=>"Id not provided"];
    }
}
