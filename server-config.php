<?php

/*
    This file contains the configuration specific to Konex.us site
*/

class Server_Config {
    const DBG_ON = true;
    const DBG_OFF = false;
    const CACHING_ON = true;
    const CACHING_OFF = false;
    
    public $password;
    public $db;
    public $user;
    public $host;
    public $db_cfg;
    
    public $auth_key;
    public $secure_auth_key;
    public $logged_in_key;
    public $nonce_key;
    public $auth_salt;
    public $secure_auth_salt;
    public $logged_in_salt;
    public $nonce_salt;
    
    public $memcached_servers;
    public $wp_cache_enabled = true;
    public $wp_cache_disabled = false;
    public $wp_caching;
    public $default_time_zone;
    public $error_level;

    public $wpdbg;
    public $dbg_log;
    public $show_errors;
    public $script_dbg;
    public $save_queries;
        
    public function __construct() {
        $this->set_db_credentials();
        $this->set_keys_and_salts();
        $this->set_memcached_servers();
        $this->set_hyperdb_cfg();
        $this->wp_caching = self::CACHING_OFF;
        $this->default_timezone_set ="America/New_York";
        $this->error_level = E_ALL;
    }
    
    public function set_db_credentials() {
        $this->password = 'P0pc0rn%461';
        $this->db = 'konexusdb';
        $this->user = 'konexus_adm';
        $this->host = '127.0.0.1';
    }

    public function set_keys_and_salts() {
        $this->auth_key = 'ZFwX|O@0[%knR*[+aN0zsJp)=-;y7ydOnDCpab^v+RJoHA+#tH.H&OJU~F*.>&>Q';
        $this->secure_auth_key  = '8|1rz&Z<gjM9a=mcvUrD;U9#,2MpLC}~DB,r=W-5QK]UpOY|gj#z[L:^c}1~.w_T';
        $this->logged_in_key    = 'J7/[0|U(7A|iDl;u&*uURf?^L.YeaPOlqvR_+WfvI#C+4,}psafO-0}d:8L$U(.[';
        $this->nonce_key        = 'eAEEQgygKbcB[Wa8z,`F~3F^uJ!N30aU|d%0ulae}3rK0)}itd,va45#Yki-wZ|r';
        $this->auth_salt        = 'Xt7p%JNk+&pJ%.L|]en;%.|>|XBl+M`I=Bg]wN`3gt|[kRYHk4Zk OlaIKXR@;~c';
        $this->secure_auth_salt = '20->e%i4Ty7PaR?<z47X9BRZr+LJMp}:iJ;@=Lu&:2|K32abU:na|-`=dLr/+IKd';
        $this->logged_in_salt   = 'Z`NDI7+vYAd27&_n;1R0G&ghox~-083gm5&yH9W[z^MCG`!NAz-~$NiF=,_/+|e0';
        $this->nonce_salt       = 'Dj^{3{Y4K4.4-3tS|9i`w}-k6:KyNm`LkEql$I-{.Y$`/?C&1R]H WK1>s+n[h!}';
        $this->wp_cache_salt    = '7c33?7NJaqrFt%+B7Rtm!|%c4Dx.f,)}nD3V|tqDLu2Zz_AQ%8DS%UdyX0B^,ALT';
    }
    
    public function set_memcached_servers() {
        $this->memcached_servers = array(
            'default' => array(
                    '127.0.0.1:11211'
            )
        );
    }

    /*
        refer to: https://codex.wordpress.org/Debugging_in_WordPress
    */
    public function set_debug_options() {
        $this->wpdbg = self::DBG_ON;
        $this->dbg_log = self::DBG_ON;
        $this->show_errors = self::DBG_ON;
        $this->script_dbg = self::DBG_ON;
        $this->save_queries = self::DBG_ON;
    }
    
    public function set_hyperdb_cfg() {
        $db_cfg = array(
            'host'     => $this->host,
            'user'     => $this->user,
            'password' => $this->password,
            'name'     => $this->db,
            'write'             => 1,
            'read'          => 1,
            'dataset'       => 'global',
            'timeout'       => 0.2,
            'lag_threshold' => 2,
        );
    }
}

