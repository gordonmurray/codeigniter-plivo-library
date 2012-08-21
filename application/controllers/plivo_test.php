<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Plivo_test extends CI_Controller
{

    public function index()
    {
        
    }

    /**
     * A function to show the account() function from the library
     * Used to retrieve a users account details 
     */
    public function account()
    {
        $this->load->library('plivo');

        /*
         * look up available number groups
         */
        $response_array = $this->plivo->account();

        if ($response_array[0] == '200')
        {
            $data["account"] = json_decode($response_array[1], TRUE);

            print_r($data["account"]);
        }
        else
        {
            /*
             * the response wasn't good, show the error
             */
            $this->api_error($response_array);
        }
    }

    /**
     * A function to show the available_number_group() from the library
     * Used to see what areas/groups have available numbers
     */
    public function available_number_groups()
    {
        $this->load->library('plivo');

        /*
         * look up available number groups
         */
        $response_array = $this->plivo->available_number_group();

        if ($response_array[0] == '200')
        {
            $data["available_number_groups"] = json_decode($response_array[1], TRUE);

            print_r($data["available_number_groups"]);
        }
        else
        {
            /*
             * the response wasn't good, show the error
             */
            $this->api_error($response_array);
        }
    }

    /**
     * A function to show the available_numbers() from the library
     * Used to list numbers available in an area
     */
    public function available_numbers()
    {
        $this->load->library('plivo');

        /*
         * look up available number groups
         */
        $response_array = $this->plivo->available_numbers('15928342876993'); // 021 area code

        if ($response_array[0] == '200')
        {
            $data["available_numbers"] = json_decode($response_array[1], TRUE);

            print_r($data["available_numbers"]);
        }
        else
        {
            /*
             * the response wasn't good, show the error
             */
            $this->api_error($response_array);
        }
    }

    /**
     * A function to show an error response
     * @param type $response
     */
    public function api_error($response)
    {
        echo "Error code " . $response[0];
        echo json_decode($response[1]);
    }

}

/* End of file Plivo_test.php */
/* Location: ./application/controllers/Plivo_test.php */