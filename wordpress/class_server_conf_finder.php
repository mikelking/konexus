<?php

/*
    This system leverages the Apache server SetEvn mod.
    SetEnv ENVIRONMENT [staging, test, production1, production2, production3]

*/


class Server_Conf_Finder {
    const FILE_SUFFIX = '-conf.php';
    const DEFAULT_CFG = 'server-config.php';

    public $server_cfg;
    public $server_name;
    public $environment;
    public $conf_file;
    
    public function __construct() {
        date_default_timezone_set("America/New_York");
        $this->get_environment();
        $this->get_config();
    }
    
    public function get_server_name() {
        return( $this->server_name = $_SERVER['SERVER_NAME'] );
    }
    
    public function get_environment() {
        $this->environment = 'default';
        if (isset($_SERVER['ENVIRONMENT'])) {
            $this->environment = $_SERVER['ENVIRONMENT'];
        }
        return($this->environment);

    }

    public function get_conf_file() {
        $config_file = __DIR__ . '/' . $this->environment . self::FILE_SUFFIX;
        if (file_exists($config_file)) {
            $this->conf_file = $config_file;
        } else {
           $this->conf_file = self::DEFAULT_CFG; 
        }
        return($this->conf_file);
    }

    public function get_config() {
        if ($this->get_conf_file()) {
            require($this->get_conf_file());
            $this->server_cfg = new Server_Config();
        } else {
            error_log ( 'Config file ' . $this->get_conf_file() . ' NOT found on ' . $this->get_server_name() . 
'. Fatal failure to require it.', 0 );
            $this->server_cfg = null;
        }
        return($this->server_cfg);
    }

    public function debug_conf_file() {        
        if (file_exists($this->get_conf_file())) {
            print('The ' . $this->conf_file . ' environment file will be included as required.' . PHP_EOL);
        } else {
            print('The ' . $this->conf_file . ' environment file was not found and can not be included as 
required.' . PHP_EOL);
        }
    }
}

