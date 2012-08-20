<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Plivo_test extends CI_Controller
{

    public function index()
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

            /*
             * Load a view to list the numners
             */
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