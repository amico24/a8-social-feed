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

    private $db_settings = 'asf-settings';

    private $db_api_info = 'asf-api-token';


    private $settings;
    
    private $api_info;

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
        $this -> settings = get_option($this -> db_settings, array());
        $this -> api_info = get_option($this -> db_api_info, array());


        $this->access_token = $this -> api_info['long_access_token'] ?? '';
        $this->ig_user_id = $this -> api_info['ig_user_id'] ?? '';
        $this->client_id = $this -> settings['client_id'] ?? '';
        $this->app_secret = $this -> settings['app_secret'] ?? '';

        $this->max_accounts = $this -> settings['max_accounts'] ?? '10';
        $this->max_posts = $this -> settings['max_posts'] ?? '10';
        $this->abs_max_posts = $this -> settings['abs_max_posts'] ?? '100';
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
     * Accesses graph api to get long-lived access token given a short-lived one
     * 
     * Requires client ID and App Secret in settings already
     * 
     * Returns true if successful and sets the long-lived token and IG user ID in the settings
     * 
     * Returns false otherwise
     * 
     * @param mixed $short_token
     * 
     * @return boolean
     */
    public function update_access_token($short_token) {
        if(empty($this->client_id) || empty($this->app_secret)){
            new A8_Social_Feed_Errors("No Client ID or App Secret Found.", 'notice-error');
            return false;
        } else {
            $token_response_json = wp_remote_get('https://graph.facebook.com/v18.0/oauth/access_token?grant_type=fb_exchange_token&client_id='.$this->client_id.'&client_secret='.$this->app_secret.'&fb_exchange_token='.$short_token);
            $token_response = json_decode($token_response_json['body'], true);
            if (!array_key_exists('access_token', $token_response)){
                new A8_Social_Feed_Errors($token_response['error']['message'], 'notice-error');
                return false;
            } else {
                $this->access_token = $token_response['access_token'];

                $fb_page_data_json = wp_remote_get('https://graph.facebook.com/v18.0/me/accounts?access_token='.$this -> access_token);
                $fb_page_data = json_decode($fb_page_data_json['body'], true);
                //var_dump($fb_page_data);
                if(array_key_exists('error',$fb_page_data)){
                    new A8_Social_Feed_Errors($fb_page_data['error']['message'], 'notice-error');
                    return false;
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
                        return false;
                    }else{
                        $this -> ig_user_id = $insta_acc_data['instagram_business_account']['id'];
                        update_option($this -> db_api_info, array (
                            'short_access_token' => '',
                            'long_access_token' => $this -> access_token, 
                            'ig_user_id' => $this -> ig_user_id
                        ));
                    }
                }
                return true;
            }
        }
    }

    /**
     * Similar to update_access_token but with client ID and App Secret as parameters rather than taking them from the settings
     * 
     * Not being used currently but im leaving it in just in case
     * 
     * @param String $short_token
     * @param String $client_id
     * @param String $app_secret
     * 
     * @return boolean
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
        if(empty($this -> access_token)){
            return 'Access token not found';
        }else {
            return $this -> access_token;
        }
        
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

}