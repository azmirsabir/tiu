<?php
namespace App;

use DateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use Ramsey\Uuid\Uuid;

class Helper
{
    ///////////////////////////////////////////////////////////
    /// only this function in NMC application
    public static function validate_msisdn($msisdn, $type = "validate")
    {
        if ($type == "validate") {
            if ((substr($msisdn, 0, 2) === '75' and count(str_split($msisdn)) === 10) or
                (substr($msisdn, 0, 3) === '075' and count(str_split($msisdn)) === 11) or
                (substr($msisdn, 0, 6) === '+96475' and count(str_split($msisdn)) === 14) or
                (substr($msisdn, 0, 5) === '96475' and count(str_split($msisdn)) === 13) or
                (substr($msisdn, 0, 7) === '0096475' and count(str_split($msisdn)) === 15)) {
                return true;
            }

            return false;

        } elseif ($type == "fix") {
            return $msisdn = "964" . substr($msisdn, -10);
        }

    }

    ///////////////////////////////////////////////////////////
    static function sendMail($receiver, $cc = "", $body = "", $subject = "")
    {
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST', '10.10.17.110');
            $mail->SMTPAuth = false;
            $mail->Username = env('MAIL_USERNAME', 'egs@korektel.com');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'null');
            $mail->Port = env('MAIL_PORT', '25');

            $mail->setFrom(env('MAIL_USERNAME', 'egs@korketel.com'), 'Korek EGS');

            if ($receiver) {
                foreach ($receiver as $rec) {
                    $mail->addAddress($rec);
                }
            }

            if ($cc) {
                foreach ($cc as $c) {
                    $mail->addCC($c);
                }
            }
            $mail->AddEmbeddedImage(public_path() . '/img/korek-telecom.png', "my-attach", public_path() . '/img/korek-telecom.png');
            $mail->isHTML(true);

            $mail->Subject = $subject;
            $mail->Body = $body;

            if (!$mail->send()) {
                Log::error(__FUNCTION__ . " : " . __CLASS__ . ' : ' . $mail->ErrorInfo);
            } else {
                Log::info(__FUNCTION__ . " : " . __CLASS__ . ' : Mail sent');
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
        }
    }
    public function sendSMS($request)
    {
        try {
            $url = config('korek_middleware.sms_gateway.url');
            $user_name = config('korek_middleware.sms_gateway.user_name');
            $password = config('korek_middleware.sms_gateway.password');
            $sms_body = $request->sms_body;
            $address = "";
            foreach (DB::table('group_members')->where('group_id', $request->group_id)->get() as $index => $item) {
                $address = $address . "<address>tel:+964" . $item->msisdn . "</address>";
            }
            $xml = "<?xml version=\"1.0\" ?>
                    <msg:outboundMessageRequest xmlns:msg=\"urn:oma:xml:rest:netapi:messaging:1\">
                        $address
                        <senderAddress>tel:NMC</senderAddress>
                        <receiptRequest>
                            <callbackData />
                        </receiptRequest>
                        <outboundSMSTextMessage>
                            <message>$sms_body</message>
                        </outboundSMSTextMessage>
                        <expiryTime>P5Y2M10DT15H</expiryTime>
                    </msg:outboundMessageRequest>";

            $client = new Client([]);
            $res = $client->post($url, [
                'body' => $xml,
                'auth' => [$user_name, $password],
                'headers' => [
                    'Accept' => 'application/xml',
                    'Content-Type' => 'application/xml; charset=UTF-8'
                ],
            ]);
            return $res->getStatusCode() === 200;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
//            error_log("\n\r\n\r" . date('d-m-Y h:i:s A') . ':: ' . __FUNCTION__ . "::" . $e->getMessage() . "::" . "\n\r", 3, 'Reservation_SMS_' . date('d-m-y', time()) . '_logs.log');
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());

//            DB_Logger::log_exception([
//                'reservation_id'=>isset($request['reservation_id'])? $request['reservation_id'] : 'NA',
//                'response'=>$e->getMessage(),
//                'request'=>json_encode($request),
//                'bscs_transaction_id'=>isset($request['bscs_transaction_id'])? $request['bscs_transaction_id'] : 'NA',
//            ]);
        }
    }
    public static function tivoli_query_ticket($ticket_id)
    {
        try {
            $wsdl_url = config('korek_middleware.tivolli_service.url') . '?wsdl';

            $client = new \SoapClient($wsdl_url, array(
                "trace" => 1,
                "exception" => 0,
                'login' => config('korek_middleware.tivoli.user'),
                'password' => config('korek_middleware.tivoli.password'),
            ));

            $client->__setLocation($wsdl_url);

            $response = $client->__soapCall('executeServiceEnquiry', [
                "serviceEnquiryRequest" => [
                    "msisdn" => '964750',
                    "transactionID" => Uuid::uuid4()->toString(),
                    "channel" => 'MASS_MARKET_MS',
                    "serviceEnquiryIndicator" => 'ENQUIRY',
                    "productSpecificParameters" => [
                        "productSpecificParameter" => [
                            [
                                'name' => 'TICKETID',
                                'value' => $ticket_id
                            ],
                        ],
                    ],
                ]
            ]);

            return array_values(array_filter($response->products->product->productSpecificParameters->productSpecificParameter, function ($parameter) {
                return $parameter->name === 'STATUS';
            }))[0]->value;
        } catch (\Exception $e) {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
            return 'ERROR';
        }
    }
    public static function send_ticket($ticket_parameters)
    {
        try {
            $transaction_id = Uuid::uuid4()->toString();
            $wsdl_url = config('korek_middleware.tivolli_service.url') . '?wsdl';

            $client = new \SoapClient($wsdl_url, array(
                "trace" => 1,
                "exception" => 0,
            ));

            $response = $client->__soapCall('executeServiceActivation', [
                "serviceActivationRequest" => [
                    "msisdn" => '964750',
                    "transactionID" => $transaction_id,
                    "channel" => 'EGS',
                    "serviceActivationIndicator" => 'SEND_TICKET',
                    "productSpecificParameters" => [
                        "productSpecificParameter" => $ticket_parameters,
                    ],
                ]

            ]);

            return array_values(array_filter($response->products->product->productSpecificParameters->productSpecificParameter, function ($parameter) {
                return $parameter->name === 'TICKETID';
            }))[0]->value;

        } catch (\Exception $e) {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
            return null;
        }
    }
    public static function get_ldap_employee($username, $unique_id = 0)
    {
        try {
            return retry(5, function () use ($username, $unique_id) {

                $ldap_server = config('ldap_auth.ldap_auth.ldap_server');
                $ldap_username = config('ldap_auth.ldap_auth.ldap_username');
                $password = config('ldap_auth.ldap_auth.ldap_password');
                //$ldap_server = "LDAP://korektel.com";
                $ldap_conn = ldap_connect($ldap_server);
                $ldap_search_format = 'Korektel' . "\\" . $ldap_username;
                ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

                if (ldap_bind($ldap_conn, $ldap_search_format, $password)) {
                    $attr = array("displayname", "mail", "mobile", "description", "title", "memberof", "physicaldeliveryofficename", "department", 'l','info','division');
//                    $attr = array("passwordhistory","streetaddress","stateorprovincename","info","company","l","buildingname","homepostaladdress","postaladdress","buildingname", "c", "cn", "co", "comment", "commonname", "company", "description", "distinguishedname", "dn", "department", "displayname", "facsimiletelephonenumber", "fax", "friendlycountryname", "givenname", "homephone", "homepostaladdress", "info", "initials", 'ipphone', "mail", "mailnickname", "rfc822mailbox", "mobile", "mobiletelephonenumber", "name", 'othertelephone', "ou", "pager", "pagertelephonenumber", "physicaldeliveryofficename", "postaladdress", 'postalcode', "postofficebox", "samaccountname", "serialnumber", "sn", "surname", "st", "stateorprovincename", "street", "streetaddress", "telephonenumber", "title", "uid", "url", 'userprincipalname', "wwwhomepage");
                    $filter = "(samaccountname=$username)";
//                    $filter = "(samaccountname=*)";
                    $results = ldap_search($ldap_conn, "dc=korektel,dc=com", $filter, $attr, 0, 0, 5);
                    $search_result = ldap_get_entries($ldap_conn, $results);
                    return ['success' => true, 'emp_info' => $search_result];
                }
                return ['success' => false, 'emp_info' => [], 'message' => 'Ldap binding not successful'];
            }, 0);
        } catch (Exception $e) {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
        }
    }
    public static function getEmployeeInfo($username)
    {
        try {
            $search_result = self::get_ldap_employee($username);
            $data = array(
                'requester_name' => $search_result[0]['displayname'][0],
                'r_job_title' => $search_result[0]['title'][0],
                'r_department' => $search_result[0]['department'][0],
                'r_workplace' => $search_result[0]['physicaldeliveryofficename'][0],
                'r_contact_number' => $search_result[0]['mobile'][0],
                'memberof' => $search_result[0]['memberof'],
            );
            return $data;
        } catch (\Exception $e) {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
        }
    }
    //run by schedule for automatically updating ticket status
    public static function check_ticket_status()
    {

        try {
            $tkt_id = 0;
            $program_array = program_array::program_array('azmir.sleman')['forms'];

            foreach ($program_array as $key => $item) {
                $table = $item['db_table'];
                $request_type = $item['title'];

                $res = DB::table($table)
                    ->whereNotNull('tkt_id')
                    ->where('status', '=', 3)
                    ->get();

                foreach ($res as $k => $val) {

                    $tkt_id = $val->tkt_id;
                    $req_id = $val->id;

                    $user=DB::table($table)->join('users',$table.".u_id",'=','users.id')
                        ->where('tkt_id',$tkt_id)
                        ->value('user_name');

                    $status = Helper::tivoli_query_ticket($tkt_id);

                    if ($status == "REJECTED") {
                        self::update_status($tkt_id,$table,'5',$user,$req_id,$request_type);
                    }elseif ($status == "CLOAPP" or $status == "APPROVED" or $status == "CLOSED") {
                        self::update_status($tkt_id,$table,'4',$user,$req_id,$request_type);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
        }

    }
    public static function schedule_mail()
    {
        $unique_id=Helper::generate_uuid();
        try {
            $res = DB::table('residency_request r')
                ->join('residency_request_expense re','r.id','re.request_id')
                ->join('users u','u.id','r.u_id')
                ->select('r.id', 'name', 're.request_id', 'r_contact','re.expire_date','user_name',DB::raw('trunc(re.expire_date)-trunc(sysdate) as days'))
                ->where('expire_date', '>', date('Y-m-d'))
                ->whereNotNull('expire_date')
                ->where('r.status',4)
                ->whereRaw('trunc(re.expire_date)-trunc(sysdate) in (30)')
                ->get();

            foreach ($res as $key => $item) {
                $receiver = [$item->user_name.'@korektel.com'];
                $cc = ['egs@korektel.com'];
                $subject = "EGS System";
                $message = "The residency of ( " . $item->name . " ) is expiring in " . $item->days . " days, on " . date("d-m-Y", strtotime($item->expire_date)) . ".";
//                $link = "https://".$_SERVER['HTTP_HOST']."/home?nav_item=backoffice-nav-item&request_id=" . $item->id . "&request_type=residency_req";
                $link = route('base',['nav_item'=>'requests-nav-item','request_id'=>$item->id,'request_type'=>'residency_req']);
                $body = view('templates.mail', ['message' => $message, 'link' => $link])->render();
                Helper::sendMail($receiver, $cc, $body, $subject);
            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
        }
    }
    static function update_status($tkt_id, $table_name, $status, $user, $req_id, $request_type)
    {
        try {
            $update = DB::table($table_name)->where('tkt_id', '=', $tkt_id)->update(['status' => $status]);
            if ($update) {
                $message = "";
                if ($status == 4) {
                    $message = "Your request for (" . $request_type . ") has been completely approved";
                } elseif ($status == 5) {
                    $message = "Your request for (" . $request_type . ") has been rejected at Tivoli";
                }

                $receiver = [$user . "@korektel.com"];
                $cc = ['egs@korektel.com'];
                $subject = "EGS system";
                $link = route('base',['nav_item'=>'requests-nav-item','request_id'=>$req_id,'request_type'=>$table_name]);
                $body = view('templates.mail', ['message' => $message, 'link' => $link])->render();
                Helper::sendMail($receiver, $cc, $body, $subject);

            }
        } catch (\Exception $e) {
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
        }
    }
    //end
    public static function generate_uuid()
    {
        return crc32(uniqid());
    }
    public static function download($file_name, $directory = false, $delete = false)
    {
        $headers = array(
            'Content-Type' => 'application/txt',
            'Cache-Control' => 'must-revalidate',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment;',
        );
        return response()->download($file_name, $file_name, $headers)->deleteFileAfterSend(true);
    }
    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    public static function check_ldap_info(){
        try {

            $ldap_info=Helper::get_ldap_employee(Auth::user()->user_name);
            $fields=['displayname'=>'Display Name','title'=>'Job title','department'=>'Department','physicaldeliveryofficename'=>'Office name','mobile'=>'Contact number','info'=>'Director name'];
            $error_fields=[];

            foreach ($fields as $field=>$field_name){
                if(!isset($ldap_info['emp_info'][0][$field][0])){
                    $error_fields[$field]=$field_name;
                }
            }

            if(empty($error_fields)){
                return ['status'=>1];
            }
            return ['status'=>0,'error_fields'=>$error_fields];
        }catch (\Exception $e){
            Log::error(__FUNCTION__." : ". __CLASS__ .' : '. $e->getMessage().' : '.$e->getLine());
            return $e->getMessage();
        }
    }
}
