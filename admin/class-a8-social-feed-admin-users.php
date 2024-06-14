<?php

namespace ASF\Admin;
//class for handling users
class A8_Social_Feed_Users {
    private $users = array ();

    /**
     * Name of option in wp_options table in database
     * @var string
     */
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

    /**
     * Adds user to settings with default options (doesnt check if user exists)
     * @param mixed $username
     * 
     * @return void
     */
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

    /**
     * Returns options for a specified user
     * @param mixed $username
     * 
     * @return array
     */
    function get_user($username){
        return $this -> users[$username];
    }

    /**
     * Returns array of users
     * @return array
     */
    function get_user_list(){
        return array_keys($this -> users);
    }

    /**
     * Updates options under a username in the database
     * 
     * $user_options needs to be formatted in the way that its stored in the options already
     * (this was made kinda badly)
     * 
     * @param mixed $user_options
     * @param string $username
     * 
     * @return bool
     */
    function update_user_options($user_options, $username = 'ALL'){

        //now why the fuck did i not specify how to use this
        if($username == 'ALL'){
            update_option($this -> db_users, $user_options);
            return true;
        } else if (in_array($username, $this -> get_user_list())){
            $this -> users [$username] = $user_options;
            update_option($this -> db_users, $this -> users);
            return true;
        } else{
            return false;
        }
        
    }

    /**
     * Deletes username from database
     * @param mixed $username
     * 
     * @return void
     */
    function delete_user($username){
        unset($this -> users[$username]);
        update_option($this -> db_users, $this -> users);
    }

    /**
     * Returns array of usernames marked as "featured" in settings
     * @return array
     */
    function get_featured_users(){
        $user_list = array();
        foreach(array_keys($this -> users) as $user){
            if($this -> users[$user]['featured'] == true){
                $user_list[] = $user;
            }
        }
        return $user_list;
    }

    /**
     * Returns array of usernames marked as "homepage" in settings
     * @return array
     */
    function get_homepage_users(){
        $user_list = array();
        foreach(array_keys($this -> users) as $user){
            if($this -> users[$user]['homepage'] == true){
                $user_list[] = $user;
            }
        }
        return $user_list;
    }

    /**
     * Returns list of users under a specified category
     * @param mixed $category
     * 
     * @return array
     */
    function get_users_in_category($category){
        $user_list = array();

        if($category=='ALL'){ //MURDER MEEEEE
			foreach($this -> users as $username => $user_info){
                //only get users that have a category assigned
                if(!empty($user_info['category'])){
                    array_push($user_list, $username);
                }
            }
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

    /**
     * Changes name of category in database
     * 
     * putting this in users class bc i need access to the users db entry to do this
     * 
     * 
     * @param mixed $old_name
     * @param mixed $new_name
     * 
     * @return [type]
     */
    function update_category_name($old_name, $new_name){
        foreach($this-> users as $username => $user_info){
            $user_categories = $user_info['category'];
            if(in_array($old_name, $user_categories)){
                $cat_index = array_search($old_name, $user_categories);
                $updated_user_categories = array_replace($user_categories, array($cat_index => $new_name));
                $this->users[$username]['category'] = $updated_user_categories;
            }
        }
        update_option($this -> db_users, $this -> users);
    }


}