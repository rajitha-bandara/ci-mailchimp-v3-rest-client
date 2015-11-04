<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * MailChimp API v3 REST client
 * 
 * 
 * @author Rajitha Bandara <rajithacbandara@gmail.com>
 * @version 1.0
 */
class Mailchimp {

    private $api_key;
    private $api_endpoint = 'https://<dc>.api.mailchimp.com/3.0/';

    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->config('mailchimp');

        $this->api_key = $this->ci->config->item('api_key');
        $this->api_endpoint = $this->ci->config->item('api_endpoint');

        list(, $datacentre) = explode('-', $this->api_key);
        $this->api_endpoint = str_replace('<dc>', $datacentre, $this->api_endpoint);
    }

    /**
     * Call an API method. Every request needs the API key
     * @param  string $httpVerb The HTTP method to be used
     * @param  string $method   The API method to call, e.g. 'lists/list'
     * @param  array  $args     An array of arguments to pass to the method. Will be json-encoded for you.
     * @return array            Associative array of json decoded API response.
     */
    public function call($httpVerb = 'POST', $method, $args = array()) {
        return $this->_raw_request($httpVerb, $method, $args);
    }

    /**
     * Performs the underlying HTTP request. 
     * @param  string $httpVerb The HTTP method to be used
     * @param  string $method   The API method to be called
     * @param  array  $args     Assoc array of parameters to be passed
     * @return array            Assoc array of decoded result
     */
    private function _raw_request($httpVerb, $method, $args = array()) {
        $url = $this->api_endpoint . $method;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_USERPWD, "user:" . $this->api_key);
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/3.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $httpVerb);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ? json_decode($result, true) : false;
    }

}
