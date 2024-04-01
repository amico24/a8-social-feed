<?php

namespace ASF\Admin;

abstract class Display {
    protected $profile_picture;
    protected $username;
    protected $account_name;
    protected $bio;
    protected $post_list;


    /**
     * Summary of __construct
     * @param mixed $api_data entire api response for 1 account
     */
    function __construct($api_data) {
        $this->profile_picture = $api_data['business_discovery']['profile_picture_url'];
        $this->username = $api_data['business_discovery']['username'];
        $this->account_name = $api_data['business_discovery']['name'];
        $this->bio = $api_data['business_discovery']['biography'];
        $this->post_list = $api_data['business_discovery']['media'];//including the pagination thing for now
    }

    abstract function generate_html();
}