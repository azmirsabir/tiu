<?php

namespace App\Traits;

use App\Utilities\OracleEntries;
use Illuminate\Support\Facades\DB;

trait LdapAuth{

    public function authenticate_ldap_login($credentials){
        if($credentials['user_name'] and $credentials['password']){
            $ldap_server = config('ldap_auth.ldap_auth.ldap_server');

            $ldap_conn = ldap_connect($ldap_server);
            $ldap_search_format = 'Korektel' . "\\" . $credentials['user_name'];

            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

            $bind = @ldap_bind($ldap_conn, $ldap_search_format, $credentials['password']);

            if ($bind) {
                return true;
            }
        }

        return false;
    }

}
