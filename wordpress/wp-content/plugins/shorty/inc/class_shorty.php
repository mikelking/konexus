<?php

if ( file_exists(__DIR__ . '/bitly-api-key.php')) {
    require(__DIR__ . '/bitly-api-key.php');
}

class Shorty {
    const API_URL      = 'http://api.bitly.com/v3/shorten';
    const API_SSL_URL  = 'https://api-ssl.bitly.com/v3/shorten';
    const CACHE_KEY    = 'bitly_url_';

    const REQUEST_FMT  = "%s?login=%s&apiKey=%s&longUrl=%s&format=json";
    
    private $payload;
    private $api_key;
    private $api_username;
    
    public $target_encoded_url;
    public $api_request;
    public $api_result;
    public $result;
    public $http;


    public function __construct() {
        // simulated wp_options... ;-S
        if (defined('API_KEY')) {
            $this->api_key = API_KEY;
        }

        if (defined('API_USERNAME')) {
            $this->api_username = API_USERNAME;
        }
        
        $this->http = new WP_Http;
        
        $this->payload = array(
            'api_username' => self::API_USERNAME,
            'api_key' => $this->api_key,
            'api_url' => $this->api_username,
            'api_ssl' => self::API_SSL_URL,
            'cache_key' => self::CACHE_KEY
        );
        
        if ( file_exists(__DIR__ . '/bitly-api-key.php')) {
            print("<!-- api deets\n" . PHP_EOL);
            print(__DIR__ . '/bitly-api-key.php');
            print("\n-->\n" . PHP_EOL);
        }
        
        $this->display_payload();
        $this->prepare_api_request();
        $this->display_bitly_api_request();
        $this->api_result = $this->http->request($this->api_request);
        $this->decode_bitly_result();
        $this->display_bitly_result();
    }

    public function prepare_api_request($url = null) {
        if (! isset($url)) {
            $url = get_permalink();
        }
        $this->display_permalink( $url );
        $this->target_encoded_url = urlencode($url);
        $this->api_request = sprintf(
            self::REQUEST_FMT, $this->payload['api_ssl'], $this->payload['api_username'], 
            $this->payload['api_key'], $this->target_encoded_url
        );
    }

    public function display_permalink( $url ) {
        if ( $url ) {
            print("<!-- PermaLink URL\n" . PHP_EOL);
            print($url);
            print("\n-->\n" . PHP_EOL);
        }
    }


    public function display_bitly_api_request() {
            print("<!-- Bitly API request\n" . PHP_EOL);
            print($this->api_request);
            print("\n-->\n" . PHP_EOL);
    }

    public function display_payload() {
        print("<!-- Shorty payload\n" . PHP_EOL);
        var_dump($this->payload);
        print("\n-->\n" . PHP_EOL);
    }

    public function display_bitly_result() {
        if($this->api_result){
            print("<!-- Shorty\n" . PHP_EOL);
            var_dump($this->result);
            print($this->target_encoded_url);
            print("\n-->\n" . PHP_EOL);
        }
    }

    public function decode_bitly_result() {
        $output = json_decode($this->api_result);
        if (isset($output->{'data'}->{'hash'})) {
            $this->result['url'] = $output->{'data'}->{'url'};
            $this->result['hash'] = $output->{'data'}->{'hash'};
            $this->result['global_hash'] = $output->{'data'}->{'global_hash'};
            $this->result['long_url'] = $output->{'data'}->{'long_url'};
            $this->result['new_hash'] = $output->{'data'}->{'new_hash'};
        }

    }

    public function get_short_url() {
        return($this->result['url']);
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

https://api-ssl.bitly.com/v3/shorten?login=etsy&apiKey=API_KEY&longUrl=http%3A%2F%2Fwww.etsy.com%2Fpeople%2FAPI_USERNAME&format=json


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
