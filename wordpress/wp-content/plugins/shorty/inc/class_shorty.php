<?php

if ( file_exists('bitly-api-key.php')) {
    require('bitly-api-key.php');
}

class Shorty {
    const API_URL      = 'http://api.bitly.com/v3/shorten';
    const API_SSL_URL  = 'https://api-ssl.bitly.com/v3/shorten';
    const CACHE_KEY    = 'bitly_url_';
    const API_KEY      = '';
    const API_USERNAME = '';
    const REQUEST_FMT  = "%s?login=%s&apiKey=%s&longUrl=%s&format=json";
    
    private $shorty_config;
    
    public $target_encoded_url;
    public $api_request;
    public $api_result;
    public $shorty_result;


    public function __construct() {
        $this->shorty_config = array(
            'api_username' => self::API_USERNAME,
            'api_key' => (defined('API_KEY')) ? API_KEY: self::API_KEY,
            'api_url' => (defined('API_USERNAME')) ? API_USERNAME: self::API_URL,
            'api_ssl' => self::API_SSL_URL,
            'cache_key' => self::CACHE_KEY
        );

    }

    public function prepare_api_request($url) {
        if (isset($url)) {
            $this->target_encoded_url = urlencode($url);
            $this->api_request = sprintf(
                self::REQUEST_FMT, $this->shorty_config['api_ssl'], $this->shorty_config['api_username'], 
                $this->shorty_config['api_key'], $this->target_encoded_url
            );
        } else {
            die("I'm sorry you can not shorten nothing. Please enter a URL and try again.");
        }
    }

    public function display_bitly_api_request() {
        if($this->api_result){
            print("<!-- Bitly API\n" . PHP_EOL);
            print($this->api_request);
            print("\n-->\n" . PHP_EOL);
        }
    }


    public function display_bitly_result() {
        if($this->api_result){
            print("<!-- Shorty\n" . PHP_EOL);
            var_dump($this->shorty_result);
            print($this->target_encoded_url);
            print("\n-->\n" . PHP_EOL);
        }
    }

    public function decode_bitly_result() {
        $output = json_decode($this->api_result);
        if (isset($output->{'data'}->{'hash'})) {
            $this->shorty_result['url'] = $output->{'data'}->{'url'};
            $this->shorty_result['hash'] = $output->{'data'}->{'hash'};
            $this->shorty_result['global_hash'] = $output->{'data'}->{'global_hash'};
            $this->shorty_result['long_url'] = $output->{'data'}->{'long_url'};
            $this->shorty_result['new_hash'] = $output->{'data'}->{'new_hash'};
        }

    }

    public function get_short_url() {
        return($this->shorty_result['url']);
    }

    public function bitly_get_request() {
        try {
            $curl_request = curl_init($this->api_request);
            curl_setopt($curl_request, CURLOPT_HEADER, 0);
            curl_setopt($curl_request, CURLOPT_TIMEOUT, 4);
            curl_setopt($curl_request, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, FALSE);
            $this->api_result = curl_exec($curl_request);
        } catch (Exception $e) {
        }

    }
}
/* sample output

https://api-ssl.bitly.com/v3/shorten?login=etsy&apiKey=R_f5464cc6d53a121bbc9c508875e85187&longUrl=http%3A%2F%2Fwww.etsy.com%2Fpeople%2Fmikelking&format=json


{ "status_code": 200, "status_txt": "OK", "data": { "long_url": "http:\/\/www.etsy.com\/people\/mikelking", "url": "http:\/\/etsy.me\/V5nwIg", "hash": "V5nwIg", "global_hash": "V5nwIh", "new_hash": 1 } }



Array
(
    [url] => http://etsy.me/V5nwIg
    [hash] => V5nwIg
    [global_hash] => V5nwIh
    [long_url] => http://www.etsy.com/people/mikelking
    [new_hash] => 1
)

*/
