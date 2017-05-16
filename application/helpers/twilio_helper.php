<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('sendSMS'))
{
    function sendSMS(){
      require('Services/Twilio.php');  
    }
	
}

/* End of file twilio_helper.php */
/* Location: ./application/helpers/twilio_helper.php */
