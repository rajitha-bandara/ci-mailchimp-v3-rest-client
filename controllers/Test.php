<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function subscribe() {
        $result = $this->mailchimp->call('POST', 'lists/LIST_ID/members', array(
            'email_address' => SUBSCRIBER_EMAIL,
            'status' => 'subscribed',
            'merge_fields' => array(
                'FNAME' => SUBSCRIBER_FIRST_NAME
            )
        ));
    }
    
    public function unsubscribe() {
        $result = $this->mailchimp->call('PATCH', "lists/LIST_ID/members/EMAIL_MD5_HASH", array(
            'status' => "unsubscribed"
        ));
    }
    
    public function add_merge_field() {
        $result = $this->mailchimp->call('POST', 'lists/LIST_ID/merge-fields', array(
            "tag" => "GENDER",
            "required" => TRUE,  
            "name" => "Gender",
            "type" => "text", // text, number, address, phone, email, date, url, imageurl, radio, dropdown, checkboxes, birthday, zip
            "default_value" => "", 
            "public" => true,
            "display_order" => 4,
            "help_text" => "Select Gender"
        ));
    }

}
