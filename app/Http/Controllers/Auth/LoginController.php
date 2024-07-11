<?php

namespace App\Http\Controllers\Auth;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Traits\LdapAuth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use LdapAuth;
    public function login(Request $request)
    {
        try {
            $request->merge(['user_name' => strtolower($request->input('user_name'))]);
            if(str_contains(strtolower($request->input('user_name')),'@korektel.com')){
                $request->merge([
                    'user_name' => str_replace("@korektel.com","",strtolower($request->input('user_name'))),
                ]);
            }//end

            if (Auth::attempt($request->only('user_name', 'password'))) {
                $request->session()->regenerate();
                Log::notice($request->user_name.' logged in');
                // return redirect()->intended('home');
                return back();
            }
            return back()->withErrors([
                'user_name' => 'The provided credentials do not match our records.',
            ]);
        }catch (\Exception $e)
        {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
        }


    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/home');
    }


    public function check_access($username){
        $ldap_res=Helper::get_ldap_employee($username);
        if($ldap_res['success']){
            $groups=$ldap_res['emp_info'][0]['memberof'];
            $city=$ldap_res['emp_info'][0]['l'][0];
            $city_id=self::get_city(strtolower($city));
            $type=[];
            if (str_contains( json_encode($groups), 'Admin_Fleet_Admins')) {
                array_push($type,"Admins");
            }
            if (str_contains( json_encode($groups), 'Admin_Fleet_Managers')) {
                array_push($type,"Managers");
            }
            if (str_contains( json_encode($groups), 'Admin_Fleet_Users')) {
                array_push($type,"Backoffice");
            }
            if (str_contains( json_encode($groups), 'IT_CIM')) {
                array_push($type,"Admins");
                array_push($type,"Managers");
                array_push($type,"Backoffice");
            }

            if(count($type) > 0) {
                if (!DB::table('users')->where('user_name', strtolower($username))->exists()) {
                    $insert=DB::table('users')->insert([
                        'user_name' => strtolower($username),
                        'password' => '$2y$10$.WgV67YvHRXElbW/peC4ou9XNKFZxtcedu6hmh6Hf6TufQemvtPjC',
                        'type' => json_encode($type),
                        'city_id' => $city_id
                    ]);
                    if($insert) {
                        return true;
                    };
                }else{
                    $update=DB::table('users')->where('user_name',strtolower($username))->update([
                        "type"=>json_encode($type),
                        'city_id'=>$city_id,
                        'status'=>1
                    ]);

                    if($update) {
                        return true;
                    };

                }
                return false;
            }
            return false;
        }

    }
    public static function get_city($city){
        $city_lookup=[
            "erbil"=>21,
            "soran"=>22,
            "duhok"=>23,
            "sulaymaniyah"=>24,
            "akre"=>25,
            "baghdad"=>26,
            "shingal"=>27,
            "basra"=>28,
            "pirmam"=>29,
            "kirkuk"=>30,
            "mosul"=>41,
            "anbar"=>121,
        ];

        return $city_lookup[$city];
    }

}
