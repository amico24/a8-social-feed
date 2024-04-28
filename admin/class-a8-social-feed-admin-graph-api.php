<?php

namespace ASF\Admin;

class A8_Social_Feed_Graph_API{
    /**
     * API Access token
     * @var string
     */
    private $access_token;

    /**
     * Instagram User ID of admin account
     * @var string
     */
    private $ig_user_id;

    /**
     * Client ID of Facebook app
     * @var string
     */
    private $client_id;

    /**
     * App secret of Facebook app
     * @var string
     */
    private $app_secret;

    private $max_accounts;
    
    private $max_posts;

    private $abs_max_posts;

    /**
     * Name of database entry that stores long-lived access token
     * @var string
     */
    private $db_access_token = 'asf_access_token';

    /**
     * Name of database entry that stores admin Instagram user ID
     * @var string
     */
    private $db_ig_user_id = 'asf_ig_user_id';

    /**
     * Name of database entry that stores client id
     * @var string
     */
    private $db_client_id = 'asf_client_id';

    /**
     * Name of database entry that stores app secret
     * @var string
     */
    private $db_app_secret = 'asf_app_secret';

    private $db_max_accounts = 'asf_max_accounts';
    
    private $db_max_posts = 'asf_max_posts';

    private $db_abs_max_posts = 'asf_abs_max_posts';



    private static $instance = null;
  
    private function __construct() { 
        //check if values are already in options
        $this->access_token = get_option($this -> db_access_token,'');
        $this->ig_user_id = get_option($this -> db_ig_user_id,'');
        $this->client_id = get_option($this -> db_client_id,'');
        $this->app_secret = get_option($this -> db_app_secret,'');

        $this->max_accounts = get_option($this -> db_max_accounts, 10);
        $this->max_posts = get_option($this -> db_max_posts, 20);
        $this->abs_max_posts = get_option($this -> db_abs_max_posts, 100);
    }
    
    /**
     * Doing singleton pattern for classes
     */
    public static function getInstance() {
        if (self::$instance == null)
        {
        self::$instance = new A8_Social_Feed_Graph_API();
        }
    
        return self::$instance;
    }

    /**
     * @param String $short_token
     * @param String $client_id
     * @param String $app_secret
     * 
     * @return bool
     */
    public function update_app_details($short_token, $client_id, $app_secret){
        $token_response_json = wp_remote_get('https://graph.facebook.com/v18.0/oauth/access_token?grant_type=fb_exchange_token&client_id='.$client_id.'&client_secret='.$app_secret.'&fb_exchange_token='.$short_token);
        $token_response = json_decode($token_response_json['body'], true);
        if (!array_key_exists('access_token', $token_response)){
            new A8_Social_Feed_Errors($token_response['error']['message'], 'notice-error');
            return false;
        } else {
            //if it works, update options and variables to working values
            $this -> access_token = $token_response['access_token'];
            update_option($this -> db_access_token,$token_response['access_token']);
            $this->client_id = $client_id;
            update_option($this -> db_client_id, $client_id);
            $this->app_secret = $app_secret;
            update_option($this -> db_app_secret, $app_secret);
            new A8_Social_Feed_Errors('Access Token Updated', 'notice-success');


            //get instagram ID
            //basically just checks if api conenction is working properly
            $fb_page_data_json = wp_remote_get('https://graph.facebook.com/v18.0/me/accounts?access_token='.$this -> access_token);
            $fb_page_data = json_decode($fb_page_data_json['body'], true);
            //var_dump($fb_page_data);
            if(array_key_exists('error',$fb_page_data)){
                new A8_Social_Feed_Errors($fb_page_data['error']['message'], 'notice-error');
            }else {
                //var_dump($fb_page_data);
                //die('end');
                $fb_page_id = $fb_page_data['data'][0]['id'];
                $insta_acc_data_json = wp_remote_get('https://graph.facebook.com/v18.0/'.$fb_page_id.'?fields=instagram_business_account&access_token='.$this -> access_token);
                $insta_acc_data = json_decode($insta_acc_data_json['body'], true);
                //var_dump($insta_acc_data);
                //die('end');
                if(array_key_exists('error',$insta_acc_data)){
                    new A8_Social_Feed_Errors($insta_acc_data['error']['message'], 'notice-error');
                }else{
                    $this -> ig_user_id = $insta_acc_data['instagram_business_account']['id'];
                    update_option($this -> db_ig_user_id, $insta_acc_data['instagram_business_account']['id']);
                }
            }
            return true;
        }
    }

    public function update_feed_settings($max_accounts, $max_posts, $abs_max_posts){
        if(is_int($max_accounts) && is_int($max_posts) && is_int($abs_max_posts)){
            update_option($this -> db_max_accounts,$max_accounts);
            update_option($this -> db_max_posts,$max_posts);
            update_option($this -> db_abs_max_posts,$abs_max_posts);
            new A8_Social_Feed_Errors('Feed Settings Updated', 'notice-success');

        } else {
            new A8_Social_Feed_Errors('Please ensure max values are ints', 'notice-error');
        }
    }

    public function get_max_accounts(){
        return $this -> max_accounts;
    }

    public function get_max_posts(){
        return $this -> max_posts;
    }

    public function get_abs_max_posts(){
        return $this -> abs_max_posts;
    }

    public function get_ig_id(){
        if(empty($this -> ig_user_id)){
            return 'Instagram ID not found';
        }
        else {
            return $this -> ig_user_id;
        }
    }

    public function get_access_token(){
        return $this -> access_token;
    }

    public function get_client_id(){
        return $this -> client_id;
    }

    public function get_app_secret(){
        return $this -> app_secret;
    }

    public function account_exists($username){
        $search_result_json = wp_remote_get('https://graph.facebook.com/v18.0/'.$this -> ig_user_id.'?fields=business_discovery.username('.$username.')&access_token='.$this -> access_token);
        $search_result = json_decode($search_result_json['body'], true);
        if(array_key_exists('error', $search_result)){
            return false;
        } else {
            return true;
        }
    }

    public function get_next_page($cursor){
        //get next page of api results
    }

}