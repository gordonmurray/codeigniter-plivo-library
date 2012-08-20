<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter Plivo Library
 *
 * A CodeIgniter library to interact with Plivo
 *
 * @package         CodeIgniter
 * @category        Libraries
 * @author          Gordon Murray, Murrion Software
 * @link            https://github.com/murrion/codeigniter-plivo-library
 * @link            http://murrion.ie
 * @license         http://www.opensource.org/licenses/mit-license.html
 */
class Plivo
{

    protected $auth_id;
    protected $auth_token;
    protected $end_point;
    protected $api_version;

    public function __construct()
    {
        $this->_ci = & get_instance();

        /*
         * Load config items
         */
        $this->_ci->load->config('plivo');

        $this->auth_id = $this->_ci->config->item('AUTH_ID');

        $this->auth_token = $this->_ci->config->item('AUTH_TOKEN');

        $this->api_version = $this->_ci->config->item('API_VERSION');

        $this->end_point = $this->_ci->config->item('END_POINT');
    }

    /**
     * Perform a lookup for available number groups/areas
     * @link http://plivo.com/docs/api/numbers/availablenumbergroup/
     * @param string $iso
     * @return type
     */
    public function available_number_group($iso = 'IE')
    {
        $url = $this->api_version . '/Account/' . $this->auth_id . '/AvailableNumberGroup/';

        $data = array(
            'country_iso' => $iso,
            'number_type' => 'local', // local, national, tollfree
            //'prefix' => '', // area code, max 5 digits
            //'region' => '',
            //'services' => 'voice,sms',
            'limit' => '20',
            'offset' => '0'
        );

        return $this->request($url, 'GET', $data);
    }

    /**
     * Make a Request 
     * @param type $path
     * @param type $method
     * @param type $vars
     */
    public function request($path, $method = "GET", $vars = array())
    {

        $encoded = "";
        foreach ($vars AS $key => $value)
            $encoded .= "$key=" . urlencode($value) . "&";
        $encoded = substr($encoded, 0, -1);

        /*
         * Create the full URL
         */
        $url = $this->end_point . '/' . $path;

        /*
         * if GET and vars, append them
         */
        if ($method == "GET")
            $url .= (FALSE === strpos($path, '?') ? "?" : "&") . $encoded;

        /*
         * initialize a new curl object
         */
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

        switch (strtoupper($method))
        {
            case "GET":
                curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded);
                break;
        }

        // send credentials
        curl_setopt($curl, CURLOPT_USERPWD, $pwd = "{$this->auth_id}:{$this->auth_token}");

        // initiate the request
        $json_response = curl_exec($curl);

        // get result code
        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        return array($response_code, $json_response);
    }

}

/* End of file Plivo.php */