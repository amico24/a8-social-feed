<?php

namespace ASF\Admin;

class A8_Social_Feed_Users {
    private $users = array ();

    private $db_users = "asf_users";

    private static $instance = null;

    private function __construct(){
        $this -> users = get_option($this -> db_users, array());
        if(empty($this -> users)){
            $this -> users = array();
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new A8_Social_Feed_Users();
        }
    
        return self::$instance;
    }

    function add_user($username){
        if(!array_key_exists($username, $this -> users)){
            $this -> users[$username] =  array(
                'type' => array(),
                'category' => array(),
                'featured' => 0,
                'homepage' => 0
            );
            update_option($this -> db_users, $this -> users);
            //var_dump($this -> users);
            //die();
        }
    }

    function get_user($username){
        return $this -> users[$username];
    }

    function get_user_list(){
        return array_keys($this -> users);
    }

    function update_user_options($user_options){
        update_option($this -> db_users, $user_options);
    }

    function delete_user($username){
        unset($this -> users[$username]);
        update_option($this -> db_users, $this -> users);
    }

    function get_featured_users(){
        $user_list = array();
        foreach(array_keys($this -> users) as $user){
            if($this -> users[$user]['featured'] == true){
                $user_list[] = $user;
            }
        }
        return $user_list;
    }

    function get_homepage_users(){
        $user_list = array();
        foreach(array_keys($this -> users) as $user){
            if($this -> users[$user]['homepage'] == true){
                $user_list[] = $user;
            }
        }
        return $user_list;
    }

    function get_users_in_category($category){
        $user_list = array();

        if($category=='ALL'){ //MURDER MEEEEE
			$user_list = $this -> get_user_list();
		} else {
			$category = explode(",",$category);
			foreach($category as $cat){
				$cat = trim($cat);
                foreach($this -> users as $username => $user_info){
                    if(in_array($cat, $user_info['category'])){
                        array_push($user_list, $username);
                    }
                }
            }
			//$user_list = array_merge(...$user_list);
			$user_list = array_values(array_unique($user_list));
		}
        return $user_list;
    }


}