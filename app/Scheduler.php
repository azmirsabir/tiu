<?php


namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Scheduler
{
    public static function car_ownership_expire(){
        try {
            $cars=DB::table('cars')
                ->select("name","model","plate_no","ownership_expire_date","location_id")
                ->where('ownership_expire_date','>=',DB::raw('trunc(sysdate + 30)'))
                ->where('ownership_expire_date','<',DB::raw('trunc(sysdate + 31)'));

            $cc=[
                "ardawan.amir@korektel.com",
                "saman.khorshid@korektel.com",
            ];
            if($cars->get()->isNotEmpty()){
                foreach ($cars->get() as $key=>$car){
                    $car_name=$car->name;
                    $car_model=$car->model;
                    $plate_no=$car->plate_no;
                    $ownership_expire_date=$car->ownership_expire_date;
                    $location_id=$car->location_id;
                    $receiver=[];
                    $message="The ownership of the car (".$plate_no." ".$car_name." ".$car_model.") will expired in 30 days on (".date('Y-m-d',strtotime($ownership_expire_date)).").";
                    foreach(DB::table('users')->where('city_id',$location_id)->get() as $i=>$user){
                        array_push($receiver,$user->user_name."@korektel.com");
                    }
                    $body=view('mail.template',['message'=>$message])->render();
                    Helper::sendMail($receiver,$cc,$body,"Fleet MS");
                }
            }
        }catch (\Exception $e){
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage());
        }
    }
    public static function car_vpi_expire(){
        try {
            $cars=DB::table('cars')
                ->select("name","model","plate_no","vpi_expire","location_id")
                ->where('vpi_expire','>=',DB::raw('trunc(sysdate + 30)'))
                ->where('vpi_expire','<',DB::raw('trunc(sysdate + 31)'));

            $cc=[
                "ardawan.amir@korektel.com",
                "saman.khorshid@korektel.com",
            ];
            if($cars->get()->isNotEmpty()){
                foreach ($cars->get() as $key=>$car){
                    $car_name=$car->name;
                    $car_model=$car->model;
                    $plate_no=$car->plate_no;
                    $vpi_expire=$car->vpi_expire;
                    $location_id=$car->location_id;
                    $receiver=[];
                    $message="The VPI of the car (".$plate_no." ".$car_name." ".$car_model.") will expired in 30 days on (".date('Y-m-d',strtotime($vpi_expire)).").";
                    foreach(DB::table('users')->where('city_id',$location_id)->get() as $i=>$user){
                        array_push($receiver,$user->user_name."@korektel.com");
                    }
                    $body=view('mail.template',['message'=>$message])->render();
                    Helper::sendMail($receiver,$cc,$body,"Fleet MS");
                }
            }
        }catch (\Exception $e){
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage());
        }
    }
    public static function driver_licence_expire(){
        $drivers=DB::table('drivers')
            ->select("name","phone","licence_expire","location_id")
            ->where('licence_expire','>=',DB::raw('trunc(sysdate + 30)'))
            ->where('licence_expire','<',DB::raw('trunc(sysdate + 31)'));

        $cc=[
            "ardawan.amir@korektel.com",
            "saman.khorshid@korektel.com",
        ];
        if($drivers->get()->isNotEmpty()){
            foreach ($drivers->get() as $key=>$driver){
                $driver_name=$driver->name;
                $phone=$driver->phone;
                $licence_expire=$driver->licence_expire;
                $location_id=$driver->location_id;
                $receiver=[];
                $message="The the driving licence of driver (name: ".$driver_name.", phone: ".$phone.") will expired in 30 days on (".date('Y-m-d',strtotime($licence_expire)).").";
                foreach(DB::table('users')->where('city_id',$location_id)->get() as $i=>$user){
                    array_push($receiver,$user->user_name."@korektel.com");
                }
                $body=view('mail.template',['message'=>$message])->render();
                Helper::sendMail($receiver,$cc,$body,"Fleet MS");
            }
        }
    }
    public static function rental_car_contract_expire(){
        $cars=DB::table('rental_cars')
            ->select("name as driver_name","plate_no","car_name as car_type","date as expire_date","location_id")
            ->where('date','>=',DB::raw('trunc(sysdate + 30)'))
            ->where('date','<',DB::raw('trunc(sysdate + 31)'));

        $cc=[
            "ardawan.amir@korektel.com",
            "saman.khorshid@korektel.com",
        ];
        if($cars->get()->isNotEmpty()){
            foreach ($cars->get() as $key=>$car){
                $driver_name=$car->driver_name;
                $plate_no=$car->plate_no;
                $car_type=$car->car_type;
                $expire_date=$car->expire_date;
                $location_id=$car->location_id;
                $receiver=[];
                $message="The contract of rental car ( Car model: ".$car_type." ".$plate_no." , Driver name : ".$driver_name.") will expired in 30 days on (".date('Y-m-d',strtotime($expire_date)).").";
                foreach(DB::table('users')->where('city_id',$location_id)->get() as $i=>$user){
                    array_push($receiver,$user->user_name."@korektel.com");
                }
                $body=view('mail.template',['message'=>$message])->render();
                Helper::sendMail($receiver,$cc,$body,"Fleet MS");
            }
        }
    }

    public static function general_schedule($data,$receiver=["azmir.sleman@korektel.com"],$cc=[],$message="",$subject="Fleet MS"){
        try {
//            $table=self::generate_table($data->get());
            $body=view('mail.template',['message'=>$message])->render();
            Helper::sendMail($receiver,$cc,$body,$subject);
        }catch (\Exception $e){
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage());
        }
    }

    public static function generate_table($data)
    {
        $table= '<table border="1" id="data">
                    <thead>
                        <tr>';

                foreach ($data as $index=>$car) {
                    foreach ($car as $i=>$prop) {
                        if($index==0) $table.='<th width="10%">'.str_replace('_',' ',ucfirst($i)).'</th>';
                    }
                }

               $table.='</tr>
                    </thead>
                    <tbody>';

                    foreach ($data as $i=>$car) {
                        $table .='<tr>';
                        foreach ($car as $prop) {
                            $table .= '<td>' . $prop . '</td>';
                        }
                        $table .='</tr>';
                    }

                    $table .='
                    </tbody>
                </table>';

                    return $table;
    }
}
